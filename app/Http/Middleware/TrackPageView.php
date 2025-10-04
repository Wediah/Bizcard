<?php

namespace App\Http\Middleware;

use App\Services\AnalyticsService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    public function __construct(
        private AnalyticsService $analyticsService
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        // Don't track certain requests
        if ($this->shouldTrack($request)) {
            $this->analyticsService->trackPageView($request);
        }
    }

    protected function shouldTrack(Request $request): bool
    {
        // Don't track AJAX requests, console commands, or specific paths
        if ($request->ajax() || app()->runningInConsole()) {
            return false;
        }

        $ignoredPaths = [
            'analytics',
            'admin',
            'horizon',
            'telescope',
        ];

        foreach ($ignoredPaths as $path) {
            if (str_contains($request->path(), $path)) {
                return false;
            }
        }

        return true;
    }
}
