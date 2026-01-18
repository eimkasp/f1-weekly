<?php

namespace App\Services;

use App\Models\PolymarketMarket;
use App\Models\Driver;
use App\Models\Race;
use App\Models\Team;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

class PolymarketService
{
    protected string $clobBaseUrl = 'https://clob.polymarket.com';
    protected string $gammaBaseUrl = 'https://gamma-api.polymarket.com';
    protected ?string $apiKey;
    protected ?string $apiSecret;
    protected ?string $passphrase;

    public function __construct()
    {
        $this->apiKey = config('services.polymarket.api_key');
        $this->apiSecret = config('services.polymarket.api_secret');
        $this->passphrase = config('services.polymarket.passphrase');
    }

    /**
     * Search for F1 related markets on Polymarket
     */
    public function searchF1Markets(string $query = 'Formula 1', int $limit = 100): array
    {
        try {
            $response = Http::get("{$this->gammaBaseUrl}/markets", [
                'tag' => 'sports',
                'active' => true,
                'limit' => $limit,
            ]);

            if (!$response->successful()) {
                Log::error('Polymarket API error', ['status' => $response->status()]);
                return [];
            }

            $markets = $response->json();
            
            // Filter for F1 related markets
            return array_filter($markets, function ($market) use ($query) {
                $title = strtolower($market['question'] ?? $market['title'] ?? '');
                $searchTerms = ['f1', 'formula 1', 'grand prix', 'verstappen', 'hamilton', 'leclerc', 'ferrari', 'red bull', 'mclaren', 'mercedes'];
                
                foreach ($searchTerms as $term) {
                    if (str_contains($title, $term)) {
                        return true;
                    }
                }
                return false;
            });
        } catch (Exception $e) {
            Log::error('Polymarket search error', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Get market details by condition ID
     */
    public function getMarket(string $conditionId): ?array
    {
        $cacheKey = "polymarket_market_{$conditionId}";
        
        return Cache::remember($cacheKey, 60, function () use ($conditionId) {
            try {
                $response = Http::get("{$this->clobBaseUrl}/markets/{$conditionId}");
                
                if ($response->successful()) {
                    return $response->json();
                }
            } catch (Exception $e) {
                Log::error('Failed to fetch market', ['conditionId' => $conditionId, 'error' => $e->getMessage()]);
            }
            return null;
        });
    }

    /**
     * Get current prices for a market
     */
    public function getMarketPrices(string $tokenId): ?array
    {
        try {
            $response = Http::get("{$this->clobBaseUrl}/price", [
                'token_id' => $tokenId,
            ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (Exception $e) {
            Log::error('Failed to fetch prices', ['tokenId' => $tokenId, 'error' => $e->getMessage()]);
        }
        return null;
    }

    /**
     * Get orderbook for a market
     */
    public function getOrderbook(string $tokenId): ?array
    {
        try {
            $response = Http::get("{$this->clobBaseUrl}/book", [
                'token_id' => $tokenId,
            ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (Exception $e) {
            Log::error('Failed to fetch orderbook', ['tokenId' => $tokenId, 'error' => $e->getMessage()]);
        }
        return null;
    }

    /**
     * Get midpoint price for a token
     */
    public function getMidpointPrice(string $tokenId): ?float
    {
        try {
            $response = Http::get("{$this->clobBaseUrl}/midpoint", [
                'token_id' => $tokenId,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return floatval($data['mid'] ?? $data['price'] ?? null);
            }
        } catch (Exception $e) {
            Log::error('Failed to fetch midpoint', ['tokenId' => $tokenId, 'error' => $e->getMessage()]);
        }
        return null;
    }

    /**
     * Sync all F1 markets to database
     */
    public function syncF1Markets(): int
    {
        $markets = $this->searchF1Markets();
        $synced = 0;

        foreach ($markets as $marketData) {
            try {
                $market = $this->syncMarket($marketData);
                if ($market) {
                    $synced++;
                }
            } catch (Exception $e) {
                Log::error('Failed to sync market', ['error' => $e->getMessage(), 'data' => $marketData]);
            }
        }

        return $synced;
    }

    /**
     * Sync a single market to database
     */
    public function syncMarket(array $data): ?PolymarketMarket
    {
        $conditionId = $data['conditionId'] ?? $data['condition_id'] ?? null;
        
        if (!$conditionId) {
            return null;
        }

        // Extract token information
        $tokens = $data['tokens'] ?? [];
        $yesToken = collect($tokens)->firstWhere('outcome', 'Yes') ?? ($tokens[0] ?? null);
        $noToken = collect($tokens)->firstWhere('outcome', 'No') ?? ($tokens[1] ?? null);

        // Get current prices if tokens exist
        $priceYes = null;
        $priceNo = null;
        
        if ($yesToken && isset($yesToken['token_id'])) {
            $priceYes = $this->getMidpointPrice($yesToken['token_id']);
        }
        if ($noToken && isset($noToken['token_id'])) {
            $priceNo = $this->getMidpointPrice($noToken['token_id']);
        }

        // Try to match with existing driver/race
        $driverId = $this->matchDriver($data['question'] ?? $data['title'] ?? '');
        $raceId = $this->matchRace($data['question'] ?? $data['title'] ?? '');
        $marketType = $this->detectMarketType($data['question'] ?? $data['title'] ?? '');

        return PolymarketMarket::updateOrCreate(
            ['condition_id' => $conditionId],
            [
                'question_id' => $data['questionId'] ?? $data['question_id'] ?? null,
                'title' => $data['question'] ?? $data['title'] ?? 'Unknown Market',
                'description' => $data['description'] ?? null,
                'category' => 'f1',
                'market_type' => $marketType,
                'driver_id' => $driverId,
                'race_id' => $raceId,
                'outcome_yes' => $yesToken['outcome'] ?? 'Yes',
                'outcome_no' => $noToken['outcome'] ?? 'No',
                'price_yes' => $priceYes,
                'price_no' => $priceNo,
                'volume' => $data['volume'] ?? $data['volumeNum'] ?? 0,
                'liquidity' => $data['liquidity'] ?? $data['liquidityNum'] ?? 0,
                'volume_24h' => $data['volume24hr'] ?? 0,
                'is_active' => ($data['active'] ?? true) && ($data['closed'] ?? false) === false,
                'end_date' => isset($data['endDate']) ? \Carbon\Carbon::parse($data['endDate']) : null,
                'tokens' => $tokens,
                'last_synced_at' => now(),
            ]
        );
    }

    /**
     * Update prices for all active markets
     */
    public function updateAllPrices(): int
    {
        $markets = PolymarketMarket::active()->get();
        $updated = 0;

        foreach ($markets as $market) {
            try {
                if ($this->updateMarketPrices($market)) {
                    $updated++;
                }
            } catch (Exception $e) {
                Log::error('Failed to update market prices', ['market_id' => $market->id, 'error' => $e->getMessage()]);
            }
        }

        return $updated;
    }

    /**
     * Update prices for a specific market
     */
    public function updateMarketPrices(PolymarketMarket $market): bool
    {
        $tokens = $market->tokens ?? [];
        
        if (empty($tokens)) {
            return false;
        }

        $yesToken = collect($tokens)->firstWhere('outcome', 'Yes') ?? ($tokens[0] ?? null);
        $noToken = collect($tokens)->firstWhere('outcome', 'No') ?? ($tokens[1] ?? null);

        $priceYes = null;
        $priceNo = null;

        if ($yesToken && isset($yesToken['token_id'])) {
            $priceYes = $this->getMidpointPrice($yesToken['token_id']);
        }
        if ($noToken && isset($noToken['token_id'])) {
            $priceNo = $this->getMidpointPrice($noToken['token_id']);
        }

        if ($priceYes !== null || $priceNo !== null) {
            $market->update([
                'price_yes' => $priceYes ?? $market->price_yes,
                'price_no' => $priceNo ?? $market->price_no,
                'last_synced_at' => now(),
            ]);
            return true;
        }

        return false;
    }

    /**
     * Match a driver from market title
     */
    protected function matchDriver(string $title): ?int
    {
        $title = strtolower($title);
        
        $drivers = Driver::where('is_active', true)->get();
        
        foreach ($drivers as $driver) {
            $lastName = strtolower($driver->last_name ?? '');
            $fullName = strtolower($driver->name ?? '');
            
            if ($lastName && str_contains($title, $lastName)) {
                return $driver->id;
            }
            if ($fullName && str_contains($title, $fullName)) {
                return $driver->id;
            }
        }

        return null;
    }

    /**
     * Match a race from market title
     */
    protected function matchRace(string $title): ?int
    {
        $title = strtolower($title);
        
        $races = Race::where('season', now()->year)->get();
        
        foreach ($races as $race) {
            $raceName = strtolower($race->name ?? '');
            $country = strtolower($race->circuit->country ?? '');
            
            if ($raceName && str_contains($title, $raceName)) {
                return $race->id;
            }
            if ($country && str_contains($title, $country)) {
                return $race->id;
            }
        }

        return null;
    }

    /**
     * Detect market type from title
     */
    protected function detectMarketType(string $title): string
    {
        $title = strtolower($title);
        
        if (str_contains($title, 'champion') || str_contains($title, 'wdc') || str_contains($title, 'world title')) {
            return 'championship';
        }
        if (str_contains($title, 'constructor') || str_contains($title, 'wcc')) {
            return 'constructor_championship';
        }
        if (str_contains($title, 'win') && (str_contains($title, 'race') || str_contains($title, 'grand prix'))) {
            return 'race_winner';
        }
        if (str_contains($title, 'podium')) {
            return 'podium';
        }
        if (str_contains($title, 'pole')) {
            return 'pole_position';
        }
        if (str_contains($title, 'fastest lap')) {
            return 'fastest_lap';
        }
        if (str_contains($title, 'points')) {
            return 'points_finish';
        }
        
        return 'other';
    }

    /**
     * Get F1 markets grouped by type
     */
    public function getGroupedMarkets(): array
    {
        $markets = PolymarketMarket::active()
            ->f1Markets()
            ->with(['driver', 'race', 'team'])
            ->orderBy('volume', 'desc')
            ->get();

        return [
            'championship' => $markets->where('market_type', 'championship')->values(),
            'constructor_championship' => $markets->where('market_type', 'constructor_championship')->values(),
            'race_winner' => $markets->where('market_type', 'race_winner')->values(),
            'podium' => $markets->where('market_type', 'podium')->values(),
            'other' => $markets->whereNotIn('market_type', ['championship', 'constructor_championship', 'race_winner', 'podium'])->values(),
        ];
    }

    /**
     * Get markets for upcoming race
     */
    public function getMarketsForNextRace(): \Illuminate\Database\Eloquent\Collection
    {
        $nextRace = Race::where('race_date', '>=', now())
            ->orderBy('race_date')
            ->first();

        if (!$nextRace) {
            return collect();
        }

        return PolymarketMarket::active()
            ->forRace($nextRace->id)
            ->orderBy('volume', 'desc')
            ->get();
    }
}
