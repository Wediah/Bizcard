<?php

namespace App\Http\Controllers;

use App\Models\PageView;
use App\Models\VisitorSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total page views
        $totalPageViews = PageView::count();

        // Page views growth (compared to the previous period)
        $previousPeriodViews = PageView::where('created_at', '<', now()->subDays(30))->count();
        $currentPeriodViews = PageView::where('created_at', '>=', now()->subDays(30))->count();
        $pageViewsGrowth = $previousPeriodViews > 0
            ? round((($currentPeriodViews - $previousPeriodViews) / $previousPeriodViews) * 100, 1)
            : 0.0;

        // Unique visitors (based on sessions)
        $uniqueVisitors = VisitorSession::select('visitor_id')->distinct()->count('visitor_id');

        // New visitors (sessions created in current period)
        $newVisitors = VisitorSession::where('created_at', '>=', now()->subDays(7))->count();

        // Average session duration - COMPATIBLE WITH ALL DATABASES
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // SQLite version
            $avgSessionDuration = VisitorSession::whereNotNull('ended_at')
                ->selectRaw("AVG(strftime('%s', ended_at) - strftime('%s', created_at)) as avg_duration")
                ->first()->avg_duration ?? 0;
        } elseif ($driver === 'pgsql') {
            // PostgreSQL version
            $avgSessionDuration = VisitorSession::whereNotNull('ended_at')
                ->selectRaw("AVG(EXTRACT(EPOCH FROM (ended_at - created_at))) as avg_duration")
                ->first()->avg_duration ?? 0;
        } else {
            // MySQL version (default)
            $avgSessionDuration = VisitorSession::whereNotNull('ended_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, created_at, ended_at)) as avg_duration')
                ->first()->avg_duration ?? 0;
        }

        $avgSessionDuration = $avgSessionDuration ? gmdate('H:i:s', $avgSessionDuration) : '00:00:00';

        // Bounce rate (sessions with only one page view)
        $totalSessions = VisitorSession::count();
        $singlePageSessionIds = PageView::select('session_id')
            ->groupBy('session_id')
            ->havingRaw('COUNT(*) = 1')
            ->pluck('session_id');
        $bounceSessions = VisitorSession::whereIn('session_id', $singlePageSessionIds)->count();
        $bounceRate = $totalSessions > 0 ? round((($bounceSessions / $totalSessions) * 100), 1) : 0.0;

        // Page views data for chart (last 30 days)
        $pageViewsData = PageView::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as views')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $pageViewLabels = $pageViewsData->pluck('date')->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('M j');
        });
        $pageViewData = $pageViewsData->pluck('views');

        // Recent visitors with their page views
        $recentVisitors = PageView::with('session')
            ->latest()
            ->take(10)
            ->get()
            ->groupBy('session_id')
            ->map(function($views) {
                $firstView = $views->first();
                return (object)[
                    'ip_address' => $firstView->ip_address ?? '',
                    'country_code' => $firstView->country_code ?? '',
                    'country' => $firstView->country ?? '',
                    'city' => $firstView->city ?? '',
                    'user_agent' => $firstView->user_agent ?? '',
                    'created_at' => $firstView->created_at ?? null,
                    'session_id' => $firstView->session_id ?? null,
                ];
            })
            ->values();

        return view('dashboard', compact(
            'totalPageViews',
            'pageViewsGrowth',
            'uniqueVisitors',
            'newVisitors',
            'avgSessionDuration',
            'bounceRate',
            'pageViewLabels',
            'pageViewData',
            'recentVisitors'
        ));
    }
}
