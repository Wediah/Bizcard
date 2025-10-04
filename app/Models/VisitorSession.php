<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VisitorSession extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'session_id',
        'visitor_id',
        'ip_address',
        'user_agent',
        'ended_at',
        'page_views_count',
        'meta',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ended_at' => 'datetime',
        'meta' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'session_id';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Get the page views for the session.
     */
    public function pageViews(): HasMany
    {
        return $this->hasMany(PageView::class, 'session_id', 'session_id');
    }

    /**
     * Scope a query to only include active sessions.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('ended_at');
    }

    /**
     * Scope a query to only include completed sessions.
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('ended_at');
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate = null)
    {
        $endDate = $endDate ?? now();
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Calculate session duration in seconds.
     */
    public function getDurationAttribute(): int
    {
        if (!$this->ended_at) {
            return 0;
        }

        return $this->created_at->diffInSeconds($this->ended_at);
    }

    /**
     * Get session duration in human readable format.
     */
    public function getDurationHumanAttribute(): string
    {
        $duration = $this->duration;

        if ($duration < 60) {
            return "{$duration} seconds";
        }

        if ($duration < 3600) {
            $minutes = floor($duration / 60);
            $seconds = $duration % 60;
            return "{$minutes}m {$seconds}s";
        }

        $hours = floor($duration / 3600);
        $minutes = floor(($duration % 3600) / 60);
        return "{$hours}h {$minutes}m";
    }

    /**
     * Check if session is active.
     */
    public function getIsActiveAttribute(): bool
    {
        return is_null($this->ended_at);
    }

    /**
     * Get the first page view of the session.
     */
    public function getFirstPageViewAttribute(): ?PageView
    {
        return $this->pageViews()->orderBy('created_at')->first();
    }

    /**
     * Get the last page view of the session.
     */
    public function getLastPageViewAttribute(): ?PageView
    {
        return $this->pageViews()->orderBy('created_at', 'desc')->first();
    }

    /**
     * End the session.
     */
    public function endSession(): bool
    {
        $this->ended_at = now();
        $this->page_views_count = $this->pageViews()->count();
        return $this->save();
    }

    /**
     * Get the simplified device type.
     */
    public function getDeviceTypeAttribute(): string
    {
        $userAgent = $this->user_agent;

        if (str_contains($userAgent, 'Mobile')) return 'Mobile';
        if (str_contains($userAgent, 'Tablet')) return 'Tablet';

        return 'Desktop';
    }

    /**
     * Get the browser name.
     */
    public function getBrowserAttribute(): string
    {
        $userAgent = $this->user_agent;

        if (str_contains($userAgent, 'Chrome')) return 'Chrome';
        if (str_contains($userAgent, 'Firefox')) return 'Firefox';
        if (str_contains($userAgent, 'Safari')) return 'Safari';
        if (str_contains($userAgent, 'Edge')) return 'Edge';
        if (str_contains($userAgent, 'Opera')) return 'Opera';

        return 'Unknown';
    }
}
