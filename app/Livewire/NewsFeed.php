<?php

namespace App\Livewire;

use App\Models\News;
use Livewire\Component;
use Livewire\WithPagination;

class NewsFeed extends Component
{
    use WithPagination;

    public int $perPage = 10;
    public ?string $category = null;
    public ?int $driverId = null;
    public ?int $teamId = null;
    public ?int $raceId = null;
    public bool $featuredOnly = false;

    protected $queryString = [
        'category' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function setCategory(?string $category): void
    {
        $this->category = $category;
        $this->resetPage();
    }

    public function getNewsProperty()
    {
        $query = News::published()
            ->with(['drivers', 'teams', 'races.circuit', 'tags'])
            ->latest('published_at');

        if ($this->category) {
            $query->where('category', $this->category);
        }

        if ($this->featuredOnly) {
            $query->featured();
        }

        if ($this->driverId) {
            $query->forDriver($this->driverId);
        }

        if ($this->teamId) {
            $query->forTeam($this->teamId);
        }

        if ($this->raceId) {
            $query->forRace($this->raceId);
        }

        return $query->paginate($this->perPage);
    }

    public function getCategoriesProperty(): array
    {
        return [
            'race_report' => 'Race Reports',
            'driver_profile' => 'Driver Profiles',
            'team_update' => 'Team Updates',
            'technical' => 'Technical Analysis',
            'breaking' => 'Breaking News',
            'rumor' => 'Rumours',
            'preview' => 'Race Previews',
            'review' => 'Race Reviews',
        ];
    }

    public function render()
    {
        return view('livewire.news-feed', [
            'news' => $this->news,
            'categories' => $this->categories,
        ]);
    }
}
