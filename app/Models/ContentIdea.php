<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentIdea extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_REJECTED = 'rejected';

    public const TYPE_RACE = 'race';
    public const TYPE_DRIVER = 'driver';
    public const TYPE_TEAM = 'team';
    public const TYPE_TECHNICAL = 'technical';
    public const TYPE_PREVIEW = 'preview';
    public const TYPE_REVIEW = 'review';
    public const TYPE_TRANSFER = 'transfer';
    public const TYPE_ANALYSIS = 'analysis';
    public const TYPE_FEATURE = 'feature';
    public const TYPE_BREAKING = 'breaking';
    public const TYPE_OPINION = 'opinion';

    protected $fillable = [
        'title',
        'description',
        'type',
        'status',
        'priority',
        'score',
        'context',
        'race_id',
        'driver_id',
        'team_id',
        'news_id',
        'scheduled_for',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'context' => 'array',
        'scheduled_for' => 'datetime',
    ];

    public function race(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    public function scopeHighScore($query, float $threshold = 80)
    {
        return $query->where('score', '>=', $threshold);
    }

    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeScheduledFor($query, $date)
    {
        return $query->whereDate('scheduled_for', $date);
    }

    // Methods
    public function approve(): void
    {
        $this->update(['status' => self::STATUS_APPROVED]);
    }

    public function reject(): void
    {
        $this->update(['status' => self::STATUS_REJECTED]);
    }

    public function markInProgress(): void
    {
        $this->update(['status' => self::STATUS_IN_PROGRESS]);
    }

    public function markPublished(News $news): void
    {
        $this->update([
            'status' => self::STATUS_PUBLISHED,
            'news_id' => $news->id,
        ]);
    }

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    public static function getTypeOptions(): array
    {
        return [
            self::TYPE_RACE => 'Race News',
            self::TYPE_DRIVER => 'Driver Profile',
            self::TYPE_TEAM => 'Team Update',
            self::TYPE_TECHNICAL => 'Technical Analysis',
            self::TYPE_PREVIEW => 'Race Preview',
            self::TYPE_REVIEW => 'Race Review',
            self::TYPE_TRANSFER => 'Transfer News',
            self::TYPE_ANALYSIS => 'Analysis',
            self::TYPE_FEATURE => 'Feature',
            self::TYPE_BREAKING => 'Breaking News',
            self::TYPE_OPINION => 'Opinion',
        ];
    }
}
