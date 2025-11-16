<div>
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Properties -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <div class="{{ app()->getLocale() === 'ar' ? 'mr-5' : 'ml-5' }} flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Total Properties') }}</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ $stats['total_properties'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Available Units -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="{{ app()->getLocale() === 'ar' ? 'mr-5' : 'ml-5' }} flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Available Units') }}</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ $stats['available_units'] }}</dd>
                        <dd class="text-xs text-gray-400">{{ $stats['occupied_units'] }} {{ __('occupied') }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Active Contracts -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-500 text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="{{ app()->getLocale() === 'ar' ? 'mr-5' : 'ml-5' }} flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Active Contracts') }}</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ $stats['active_contracts'] }}</dd>
                        <dd class="text-xs text-gray-400">{{ $stats['total_tenants'] }} {{ __('tenants') }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-yellow-500 text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="{{ app()->getLocale() === 'ar' ? 'mr-5' : 'ml-5' }} flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Total Revenue') }}</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ number_format($stats['total_revenue'], 2) }}</dd>
                        <dd class="text-xs text-gray-400">{{ $stats['pending_invoices'] }} {{ __('pending invoices') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if($stats['overdue_invoices'] > 0 || $stats['pending_maintenance'] > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        @if($stats['overdue_invoices'] > 0)
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }}">
                    <h3 class="text-sm font-medium text-red-800">{{ __('Overdue Invoices') }}</h3>
                    <p class="mt-1 text-sm text-red-700">{{ __('You have :count overdue invoices that need attention.', ['count' => $stats['overdue_invoices']]) }}</p>
                </div>
            </div>
        </div>
        @endif

        @if($stats['pending_maintenance'] > 0)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }}">
                    <h3 class="text-sm font-medium text-yellow-800">{{ __('Pending Maintenance') }}</h3>
                    <p class="mt-1 text-sm text-yellow-700">{{ __('You have :count maintenance requests pending.', ['count' => $stats['pending_maintenance']]) }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Contracts -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">{{ __('Recent Contracts') }}</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentContracts as $contract)
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $contract->tenant->name ?? __('N/A') }}</p>
                            <p class="text-sm text-gray-500">{{ $contract->unit->property->name ?? '' }} - {{ $contract->unit->unit_number ?? '' }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            @if($contract->status === 'active') bg-green-100 text-green-800
                            @elseif($contract->status === 'draft') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($contract->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    {{ __('No contracts yet') }}
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">{{ __('Recent Payments') }}</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentPayments as $payment)
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $payment->invoice->contract->tenant->name ?? __('N/A') }}</p>
                            <p class="text-sm text-gray-500">{{ $payment->payment_method }} - {{ $payment->created_at->format('M d, Y') }}</p>
                        </div>
                        <span class="text-sm font-semibold text-green-600">{{ number_format($payment->amount, 2) }} AED</span>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    {{ __('No payments yet') }}
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
