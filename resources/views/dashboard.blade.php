<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Stats Cards -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <!-- Total Page Views Card -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Page Views</p>
                        <h3 class="mt-2 text-3xl font-bold text-neutral-900 dark:text-white">{{ number_format($totalPageViews) }}</h3>
                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                            <span class="@if($pageViewsGrowth >= 0) text-green-600 @else text-red-600 @endif font-medium">
                                {{ $pageViewsGrowth >= 0 ? '+' : '' }}{{ $pageViewsGrowth }}%
                            </span>
                            from last period
                        </p>
                    </div>
                    <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900/30">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Unique Visitors Card -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Unique Visitors</p>
                        <h3 class="mt-2 text-3xl font-bold text-neutral-900 dark:text-white">{{ number_format($uniqueVisitors) }}</h3>
                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                            <span class="font-medium text-green-600">{{ number_format($newVisitors) }}</span>
                            new this week
                        </p>
                    </div>
                    <div class="rounded-full bg-green-100 p-3 dark:bg-green-900/30">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Bounce Rate & Session Duration Card -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Bounce Rate</p>
                        <h3 class="mt-2 text-3xl font-bold text-neutral-900 dark:text-white">{{ $bounceRate }}%</h3>
                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                            Avg session: <span class="font-medium">{{ $avgSessionDuration }}</span>
                        </p>
                    </div>
                    <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900/30">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart and Recent Visitors -->
        <div class="grid gap-4 lg:grid-cols-2">
            <!-- Page Views Chart -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <h3 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-white">Page Views (Last 30 Days)</h3>
                <canvas id="pageViewsChart" class="w-full" height="200"></canvas>
            </div>

            <!-- Recent Visitors -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <h3 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-white">Recent Visitors</h3>
                <div class="space-y-3 max-h-[400px] overflow-y-auto">
                    @forelse($recentVisitors as $visitor)
                        <div class="flex items-start gap-3 rounded-lg border border-neutral-200 p-3 dark:border-neutral-700">
                            <div class="flex-shrink-0">
                                @if($visitor->country_code)
                                    <span class="text-2xl">{{ \App\Services\CountryFlagService::getFlag($visitor->country_code) }}</span>
                                @else
                                    <div class="h-8 w-8 rounded-full bg-neutral-200 dark:bg-neutral-700"></div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-neutral-900 dark:text-white">
                                    {{ $visitor->city ?? 'Unknown' }}, {{ $visitor->country ?? 'Unknown' }}
                                </p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400 truncate">
                                    {{ $visitor->ip_address }}
                                </p>
                                <p class="text-xs text-neutral-400 dark:text-neutral-500">
                                    {{ $visitor->created_at?->diffForHumans() ?? 'Unknown time' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center">
                            <p class="text-sm text-neutral-500 dark:text-neutral-400">No visitors yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('pageViewsChart');

                if (ctx) {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: @json($pageViewLabels),
                            datasets: [{
                                label: 'Page Views',
                                data: @json($pageViewData),
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
</x-layouts.app>
