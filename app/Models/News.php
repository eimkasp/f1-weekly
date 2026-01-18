<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Spatie\Tags\HasTags;

class News extends Model
{
    use HasFactory, HasTags;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'summary',
        'featured_image',
        'featured_image_alt',
        'category',
        'status',
        'ai_generated',
        'ai_model',
        'sentiment_score',
        'sentiment_label',
        'seo_title',
        'seo_description',
        'structured_data',
        'views',
        'author_id',
        'published_at',
    ];

    protected $casts = [
        'ai_generated' => 'boolean',
        'sentiment_score' => 'decimal:2',
        'structured_data' => 'array',
        'views' => 'integer',
        'published_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function drivers(): BelongsToMany
    {
        return $this->belongsToMany(Driver::class, 'driver_news');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_news');
    }

    public function races(): BelongsToMany
    {
        return $this->belongsToMany(Race::class, 'race_news');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePendingReview($query)
    {
        return $query->where('status', 'pending_review');
    }

    public function scopeAiGenerated($query)
    {
        return $query->where('ai_generated', true);
    }

    public function scopeHuman($query)
    {
        return $query->where('ai_generated', false);
    }

    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('published_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published' && $this->published_at && $this->published_at <= now();
    }

    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->content ?? ''));
        return max(1, (int) ceil($wordCount / 200)); // 200 words per minute
    }

    public function getCategoryDisplayAttribute(): string
    {
        return match($this->category) {
            'race' => 'Race News',
            'qualifying' => 'Qualifying',
            'transfer' => 'Transfers',
            'analysis' => 'Analysis',
            'preview' => 'Race Preview',
            'review' => 'Race Review',
            'technical' => 'Technical',
            'breaking' => 'Breaking',
            'feature' => 'Feature',
            default => ucfirst($this->category ?? 'News'),
        };
    }

    public function getCategoryColorAttribute(): string
    {
        return match($this->category) {
            'race' => 'primary',
            'qualifying' => 'info',
            'transfer' => 'warning',
            'analysis' => 'secondary',
            'preview' => 'success',
            'review' => 'success',
            'technical' => 'gray',
            'breaking' => 'danger',
            'feature' => 'purple',
            default => 'gray',
        };
    }

    public function getMetaDescriptionAttribute(): string
    {
        return $this->seo_description 
            ?? $this->excerpt 
            ?? Str::limit(strip_tags($this->content ?? ''), 160);
    }

    // Methods
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function publish(): void
    {
        $this->update([
            'status' => 'published',
            'published_at' => $this->published_at ?? now(),
        ]);
    }

    public function unpublish(): void
    {
        $this->update(['status' => 'draft']);
    }

    public function generateStructuredData(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $this->title,
            'description' => $this->meta_description,
            'image' => $this->featured_image,
            'datePublished' => $this->published_at?->toIso8601String(),
            'dateModified' => $this->updated_at->toIso8601String(),
            'author' => [
                '@type' => $this->ai_generated ? 'Organization' : 'Person',
                'name' => $this->ai_generated ? 'F1 Weekly AI' : ($this->author?->name ?? 'F1 Weekly'),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'F1 Weekly',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => config('app.url') . '/images/logo.png',
                ],
            ],
        ];
    }
}
