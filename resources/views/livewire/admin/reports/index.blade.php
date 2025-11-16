@section('header')
    Financial Reports
@endsection

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Financial Reports</h1>
            <p class="mt-2 text-gray-600">Comprehensive overview of revenue, expenses, and occupancy metrics</p>
        </div>

        <!-- Date Range Filters -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                    <select wire:model.live="dateRange" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="year">This Year</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>

                @if($dateRange === 'custom')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" wire:model.live="startDate" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" wire:model.live="endDate" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                @endif
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Revenue -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">${{ number_format($revenue['total'], 2) }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 text-xs text-gray-500">
                    <span class="text-green-600 font-medium">Rental: ${{ number_format($revenue['rental'], 2) }}</span>
                </div>
            </div>

            <!-- Total Expenses -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Expenses</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">${{ number_format($expenses['total'], 2) }}</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-lg">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 text-xs text-gray-500">
                    <span class="text-red-600 font-medium">Maintenance: ${{ number_format($expenses['maintenance'], 2) }}</span>
                </div>
            </div>

            <!-- Net Income -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Net Income</p>
                        <p class="mt-2 text-3xl font-bold {{ $netIncome >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ${{ number_format($netIncome, 2) }}
                        </p>
                    </div>
                    <div class="p-3 {{ $netIncome >= 0 ? 'bg-blue-100' : 'bg-orange-100' }} rounded-lg">
                        <svg class="w-8 h-8 {{ $netIncome >= 0 ? 'text-blue-600' : 'text-orange-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 text-xs text-gray-500">
                    Revenue - Expenses
                </div>
            </div>

            <!-- Collection Rate -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Collection Rate</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $collectionRate }}%</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $collectionRate }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Revenue vs Expenses Trend -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue vs Expenses (6 Month Trend)</h3>
                <canvas id="revenueExpenseChart" height="300"></canvas>
            </div>

            <!-- Revenue Breakdown -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Breakdown</h3>
                <canvas id="revenueBreakdownChart" height="300"></canvas>
            </div>
        </div>

        <!-- Occupancy & Top Properties -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Occupancy Status -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Occupancy Status</h3>

                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Overall Occupancy Rate</span>
                        <span class="text-2xl font-bold text-blue-600">{{ $occupancy['rate'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: {{ $occupancy['rate'] }}%"></div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Occupied</span>
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ $occupancy['occupied'] }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Available</span>
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ $occupancy['available'] }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg border border-orange-200">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-orange-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Under Maintenance</span>
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ $occupancy['maintenance'] }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-gray-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Total Units</span>
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ $occupancy['total_units'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Top Properties by Revenue -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Top Properties by Revenue</h3>

                @if($topProperties->count() > 0)
                    <div class="space-y-4">
                        @foreach($topProperties as $index => $property)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $index === 0 ? 'bg-yellow-100 text-yellow-700' : ($index === 1 ? 'bg-gray-100 text-gray-700' : ($index === 2 ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700')) }} font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                                <span class="font-medium text-gray-900">{{ $property->name }}</span>
                            </div>
                            <span class="text-lg font-bold text-green-600">${{ number_format($property->total_revenue, 2) }}</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No property data available for this period</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Export Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Export Reports</h3>
                    <p class="text-sm text-gray-600 mt-1">Download detailed reports in your preferred format</p>
                </div>
                <div class="flex space-x-3">
                    <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>PDF</span>
                    </button>
                    <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Excel</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('livewire:navigated', function() {
        initCharts();
    });

    function initCharts() {
        // Revenue vs Expenses Chart
        const revenueExpenseCtx = document.getElementById('revenueExpenseChart');
        if (revenueExpenseCtx) {
            new Chart(revenueExpenseCtx, {
                type: 'line',
                data: {
                    labels: @json(collect($monthlyRevenue)->pluck('month')),
                    datasets: [
                        {
                            label: 'Revenue',
                            data: @json(collect($monthlyRevenue)->pluck('amount')),
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Expenses',
                            data: @json(collect($monthlyExpenses)->pluck('amount')),
                            borderColor: 'rgb(239, 68, 68)',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }

        // Revenue Breakdown Pie Chart
        const breakdownCtx = document.getElementById('revenueBreakdownChart');
        if (breakdownCtx) {
            new Chart(breakdownCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Rental Income', 'Maintenance', 'Other'],
                    datasets: [{
                        data: [
                            {{ $revenue['rental'] }},
                            {{ $revenue['maintenance'] }},
                            {{ $revenue['other'] }}
                        ],
                        backgroundColor: [
                            'rgb(34, 197, 94)',
                            'rgb(59, 130, 246)',
                            'rgb(168, 85, 247)'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += '$' + context.parsed.toLocaleString();
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // Initialize on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCharts);
    } else {
        initCharts();
    }
</script>
@endpush
