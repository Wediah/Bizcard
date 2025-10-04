<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageView extends Model
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
        'url',
        'referrer',
        'country_code',
        'country',
        'city',
        'meta',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'meta' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the session that owns the page view.
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(VisitorSession::class, 'session_id', 'session_id');
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
     * Scope a query to filter by visitor.
     */
    public function scopeByVisitor($query, $visitorId)
    {
        return $query->where('visitor_id', $visitorId);
    }

    /**
     * Scope a query to filter by session.
     */
    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Get the simplified user agent (browser name).
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
     * Get the page name from URL.
     */
    public function getPageNameAttribute(): string
    {
        $path = parse_url($this->url, PHP_URL_PATH);
        return $path ? ltrim($path, '/') : 'Home';
    }
}
