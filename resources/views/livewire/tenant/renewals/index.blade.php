<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('My Renewal Offers') }}</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                {{ __('View and respond to lease renewal offers from your landlord') }}
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="~bg-blue-50 dark:~bg-blue-900/20 p-6 rounded-lg border border-blue-200 dark:border-blue-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600 dark:text-blue-400">{{ __('Pending') }}</p>
                        <p class="text-3xl font-bold text-blue-900 dark:text-blue-100 mt-2">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="p-3 ~bg-blue-100 dark:~bg-blue-800 rounded-full">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="~bg-green-50 dark:~bg-green-900/20 p-6 rounded-lg border border-green-200 dark:border-green-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600 dark:text-green-400">{{ __('Accepted') }}</p>
                        <p class="text-3xl font-bold text-green-900 dark:text-green-100 mt-2">{{ $stats['accepted'] }}</p>
                    </div>
                    <div class="p-3 ~bg-green-100 dark:~bg-green-800 rounded-full">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="~bg-gray-50 dark:~bg-gray-900/20 p-6 rounded-lg border border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Expired') }}</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $stats['expired'] }}</p>
                    </div>
                    <div class="p-3 ~bg-gray-100 dark:~bg-gray-800 rounded-full">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="mb-6">
            <select wire:model.live="statusFilter" class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:~bg-gray-800 dark:text-white">
                <option value="all">{{ __('All Offers') }}</option>
                <option value="sent">{{ __('New Offers') }}</option>
                <option value="viewed">{{ __('Viewed') }}</option>
                <option value="accepted">{{ __('Accepted') }}</option>
                <option value="rejected">{{ __('Rejected') }}</option>
                <option value="negotiating">{{ __('Negotiating') }}</option>
            </select>
        </div>

        <!-- Offers List -->
        <div class="space-y-4">
            @forelse($offers as $offer)
                <div class="~bg-white dark:~bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $offer->currentContract->unit->property->name ?? 'Property' }} - {{ __('Unit') }} {{ $offer->currentContract->unit->unit_number }}
                                </h3>
                                <span class="px-3 py-1 text-xs font-medium rounded-full
                                    @if($offer->status === 'sent') ~bg-blue-100 text-blue-800 dark:~bg-blue-900 dark:text-blue-200
                                    @elseif($offer->status === 'viewed') ~bg-purple-100 text-purple-800 dark:~bg-purple-900 dark:text-purple-200
                                    @elseif($offer->status === 'accepted') ~bg-green-100 text-green-800 dark:~bg-green-900 dark:text-green-200
                                    @elseif($offer->status === 'rejected') ~bg-red-100 text-red-800 dark:~bg-red-900 dark:text-red-200
                                    @elseif($offer->status === 'negotiating') ~bg-yellow-100 text-yellow-800 dark:~bg-yellow-900 dark:text-yellow-200
                                    @else ~bg-gray-100 text-gray-800 dark:~bg-gray-900 dark:text-gray-200
                                    @endif">
                                    {{ ucfirst($offer->status) }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('Current Rent') }}</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">AED {{ number_format($offer->current_rent_amount, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('Proposed Rent') }}</p>
                                    <p class="font-semibold
                                        @if($offer->proposed_rent_amount > $offer->current_rent_amount) text-red-600 dark:text-red-400
                                        @elseif($offer->proposed_rent_amount < $offer->current_rent_amount) text-green-600 dark:text-green-400
                                        @else text-gray-900 dark:text-white
                                        @endif">
                                        AED {{ number_format($offer->proposed_rent_amount, 2) }}
                                        @if($offer->rent_increase_percentage != 0)
                                            ({{ $offer->rent_increase_percentage > 0 ? '+' : '' }}{{ number_format($offer->rent_increase_percentage, 1) }}%)
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('Duration') }}</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $offer->proposed_duration_months }} {{ __('months') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('Expires On') }}</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $offer->offer_expiry_date->format('M d, Y') }}</p>
                                </div>
                            </div>

                            @if($offer->offer_expiry_date < now() && in_array($offer->status, ['sent', 'viewed']))
                                <div class="mt-4 p-3 ~bg-red-50 dark:~bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                    <p class="text-sm text-red-700 dark:text-red-300">
                                        <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ __('This offer has expired') }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="ml-4">
                            <a href="{{ route('tenant.renewals.show', $offer->id) }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white ~bg-blue-600 hover:~bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('View Details') }}
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 ~bg-white dark:~bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="mt-4 text-gray-500 dark:text-gray-400">{{ __('No renewal offers found') }}</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($offers->hasPages())
            <div class="mt-6">
                {{ $offers->links() }}
            </div>
        @endif
    </div>
</div>
