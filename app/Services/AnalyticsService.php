<?php

namespace App\Services;

use App\Models\PageView;
use App\Models\VisitorSession;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stevebauman\Location\Facades\Location;

class AnalyticsService
{
    /**
     * Track a page view.
     */
    public function trackPageView(Request $request): PageView
    {
        $visitorId = $this->getVisitorId($request);
        $sessionId = $this->getSessionId($request);

        // Get location data
        $location = $this->getLocationData($request);

        // Create or update session
        $session = $this->getOrCreateSession($sessionId, $visitorId, $request, $location);

        // Create page view
        $pageView = PageView::create([
            'session_id' => $sessionId,
            'visitor_id' => $visitorId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'referrer' => $request->header('referer'),
            'country_code' => $location['country_code'] ?? null,
            'country' => $location['country'] ?? null,
            'city' => $location['city'] ?? null,
            'meta' => [
                'method' => $request->method(),
                'is_ajax' => $request->ajax(),
                'is_secure' => $request->secure(),
            ],
        ]);

        return $pageView;
    }

    /**
     * Get or create visitor session.
     */
    protected function getOrCreateSession(string $sessionId, string $visitorId, Request $request, array $location): VisitorSession
    {
        $session = VisitorSession::where('session_id', $sessionId)->first();

        if (!$session) {
            $session = VisitorSession::create([
                'session_id' => $sessionId,
                'visitor_id' => $visitorId,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'meta' => [
                    'location' => $location,
                    'initial_referrer' => $request->header('referer'),
                ],
            ]);
        }

        return $session;
    }

    /**
     * Get visitor ID from cookie or create new one.
     */
    protected function getVisitorId(Request $request): string
    {
        $visitorId = $request->cookie('visitor_id');

        if (!$visitorId) {
            $visitorId = Str::uuid()->toString();
        }

        return $visitorId;
    }

    /**
     * Get session ID from cookie or create new one.
     */
    protected function getSessionId(Request $request): string
    {
        $sessionId = $request->cookie('session_id');

        if (!$sessionId) {
            $sessionId = Str::uuid()->toString();
        }

        return $sessionId;
    }

    /**
     * Get location data from IP address.
     */
    protected function getLocationData(Request $request): array
    {
        if ($position = Location::get($request->ip())) {
            return [
                'country_code' => $position->countryCode,
                'country' => $position->countryName,
                'city' => $position->cityName,
                'region' => $position->regionName,
                'latitude' => $position->latitude,
                'longitude' => $position->longitude,
            ];
        }

        return [];
    }

    /**
     * End a visitor session.
     */
    public function endSession(string $sessionId): bool
    {
        $session = VisitorSession::where('session_id', $sessionId)->first();

        if ($session && !$session->ended_at) {
            return $session->endSession();
        }

        return false;
    }

    /**
     * Get analytics summary for dashboard.
     */
    public function getDashboardSummary($days = 30): array
    {
        $startDate = now()->subDays($days);

        $totalPageViews = PageView::where('created_at', '>=', $startDate)->count();
        $uniqueVisitors = VisitorSession::where('created_at', '>=', $startDate)
            ->distinct('visitor_id')
            ->count();
        $totalSessions = VisitorSession::where('created_at', '>=', $startDate)->count();

        $avgSessionDuration = VisitorSession::where('created_at', '>=', $startDate)
            ->whereNotNull('ended_at')
            ->avg(DB::raw('TIMESTAMPDIFF(SECOND, created_at, ended_at)')) ?? 0;

        return [
            'total_page_views' => $totalPageViews,
            'unique_visitors' => $uniqueVisitors,
            'total_sessions' => $totalSessions,
            'avg_session_duration' => $avgSessionDuration,
        ];
    }
}
