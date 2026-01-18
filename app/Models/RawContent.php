<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RawContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_source_id',
        'external_id',
        'title',
        'content',
        'url',
        'author',
        'image',
        'external_published_at',
        'status',
        'relevance_score',
        'news_id',
    ];

    protected $casts = [
        'external_published_at' => 'datetime',
        'relevance_score' => 'decimal:2',
    ];

    public function newsSource(): BelongsTo
    {
        return $this->belongsTo(NewsSource::class);
    }

    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessed($query)
    {
        return $query->where('status', 'processed');
    }

    public function scopeHighRelevance($query, float $threshold = 0.7)
    {
        return $query->where('relevance_score', '>=', $threshold);
    }
}
