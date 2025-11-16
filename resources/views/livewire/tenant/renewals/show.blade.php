<div>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('tenant.renewals.index') }}" class="text-blue-600 hover:text-blue-700 mb-4 inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ __('Back to Offers') }}
            </a>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mt-4">{{ __('Renewal Offer Details') }}</h1>
        </div>

        @if(session()->has('message'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <p class="text-green-700 dark:text-green-300">{{ session('message') }}</p>
            </div>
        @endif

        <!-- Status Banner -->
        <div class="mb-6 p-4 rounded-lg
            @if($offer->status === 'accepted') bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800
            @elseif($offer->status === 'rejected') bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800
            @elseif($offer->status === 'negotiating') bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800
            @elseif($offer->offer_expiry_date < now()) bg-gray-50 dark:bg-gray-900/20 border border-gray-200 dark:border-gray-800
            @else bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800
            @endif">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-semibold
                        @if($offer->status === 'accepted') text-green-700 dark:text-green-300
                        @elseif($offer->status === 'rejected') text-red-700 dark:text-red-300
                        @elseif($offer->status === 'negotiating') text-yellow-700 dark:text-yellow-300
                        @else text-blue-700 dark:text-blue-300
                        @endif">
                        {{ ucfirst($offer->status) }}
                    </p>
                    @if($offer->offer_expiry_date < now() && in_array($offer->status, ['sent', 'viewed']))
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('This offer expired on') }} {{ $offer->offer_expiry_date->format('F j, Y') }}</p>
                    @elseif(in_array($offer->status, ['sent', 'viewed']))
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Valid until') }} {{ $offer->offer_expiry_date->format('F j, Y') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Property Details -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">{{ __('Property Information') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Property') }}</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $offer->currentContract->unit->property->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Unit') }}</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $offer->currentContract->unit->unit_number ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Current Lease End Date') }}</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $offer->currentContract->end_date->format('F j, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Landlord') }}</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $offer->landlord->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Rental Terms Comparison -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">{{ __('Rental Terms') }}</h2>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left py-3 text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Term') }}</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Current') }}</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Proposed') }}</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Change') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr>
                            <td class="py-4 text-sm text-gray-900 dark:text-white">{{ __('Monthly Rent') }}</td>
                            <td class="py-4 text-sm font-semibold text-gray-900 dark:text-white">AED {{ number_format($offer->current_rent_amount, 2) }}</td>
                            <td class="py-4 text-sm font-semibold text-gray-900 dark:text-white">AED {{ number_format($offer->proposed_rent_amount, 2) }}</td>
                            <td class="py-4 text-sm font-semibold
                                @if($offer->rent_increase_percentage > 0) text-red-600 dark:text-red-400
                                @elseif($offer->rent_increase_percentage < 0) text-green-600 dark:text-green-400
                                @else text-gray-900 dark:text-white
                                @endif">
                                {{ $offer->rent_increase_percentage > 0 ? '+' : '' }}{{ number_format($offer->rent_increase_percentage, 1) }}%
                                (AED {{ number_format($offer->proposed_rent_amount - $offer->current_rent_amount, 2) }})
                            </td>
                        </tr>
                        <tr>
                            <td class="py-4 text-sm text-gray-900 dark:text-white">{{ __('Lease Duration') }}</td>
                            <td class="py-4 text-sm font-semibold text-gray-900 dark:text-white">{{ $offer->currentContract->duration_months }} {{ __('months') }}</td>
                            <td class="py-4 text-sm font-semibold text-gray-900 dark:text-white">{{ $offer->proposed_duration_months }} {{ __('months') }}</td>
                            <td class="py-4 text-sm text-gray-500 dark:text-gray-400">-</td>
                        </tr>
                        <tr>
                            <td class="py-4 text-sm text-gray-900 dark:text-white">{{ __('Start Date') }}</td>
                            <td class="py-4 text-sm font-semibold text-gray-900 dark:text-white">{{ $offer->currentContract->start_date->format('M d, Y') }}</td>
                            <td class="py-4 text-sm font-semibold text-gray-900 dark:text-white">{{ $offer->proposed_start_date->format('M d, Y') }}</td>
                            <td class="py-4 text-sm text-gray-500 dark:text-gray-400">-</td>
                        </tr>
                        <tr>
                            <td class="py-4 text-sm text-gray-900 dark:text-white">{{ __('End Date') }}</td>
                            <td class="py-4 text-sm font-semibold text-gray-900 dark:text-white">{{ $offer->currentContract->end_date->format('M d, Y') }}</td>
                            <td class="py-4 text-sm font-semibold text-gray-900 dark:text-white">{{ $offer->proposed_end_date->format('M d, Y') }}</td>
                            <td class="py-4 text-sm text-gray-500 dark:text-gray-400">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <p class="text-sm font-medium text-blue-900 dark:text-blue-100">{{ __('Total Contract Value') }}</p>
                <p class="text-2xl font-bold text-blue-900 dark:text-blue-100 mt-1">
                    AED {{ number_format($offer->proposed_rent_amount * $offer->proposed_duration_months, 2) }}
                </p>
            </div>
        </div>

        <!-- Terms and Conditions -->
        @if($offer->special_terms)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">{{ __('Terms and Conditions') }}</h2>
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $offer->special_terms }}</p>
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        @if(in_array($offer->status, ['sent', 'viewed']) && $offer->offer_expiry_date >= now())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">{{ __('Your Response') }}</h2>

                <div class="space-y-4">
                    <!-- Accept Button -->
                    <button wire:click="acceptOffer"
                            wire:confirm="Are you sure you want to accept this renewal offer?"
                            class="w-full px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-sm">
                        {{ __('Accept Offer') }}
                    </button>

                    <!-- Counter Offer Toggle -->
                    <button wire:click="$toggle('showCounterOfferForm')"
                            type="button"
                            class="w-full px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg shadow-sm">
                        {{ __('Make Counter Offer') }}
                    </button>

                    @if($showCounterOfferForm)
                        <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Counter Offer Amount (AED)') }}
                                </label>
                                <input type="number"
                                       wire:model="counter_offer_amount"
                                       step="0.01"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-800 dark:text-white">
                                @error('counter_offer_amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Notes (optional)') }}
                                </label>
                                <textarea wire:model="tenant_response_notes"
                                          rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-800 dark:text-white"></textarea>
                                @error('tenant_response_notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <button wire:click="submitCounterOffer"
                                    class="w-full px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg shadow-sm">
                                {{ __('Submit Counter Offer') }}
                            </button>
                        </div>
                    @endif

                    <!-- Reject Section -->
                    <details class="border border-gray-200 dark:border-gray-700 rounded-lg">
                        <summary class="px-4 py-3 cursor-pointer text-red-600 dark:text-red-400 font-medium">
                            {{ __('Reject this offer') }}
                        </summary>
                        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Reason for rejection (required)') }}
                            </label>
                            <textarea wire:model="tenant_response_notes"
                                      rows="3"
                                      placeholder="{{ __('Please explain why you are rejecting this offer...') }}"
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-800 dark:text-white mb-4"></textarea>
                            @error('tenant_response_notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                            <button wire:click="rejectOffer"
                                    wire:confirm="Are you sure you want to reject this renewal offer?"
                                    class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-sm">
                                {{ __('Confirm Rejection') }}
                            </button>
                        </div>
                    </details>
                </div>
            </div>
        @elseif($offer->status === 'accepted')
            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800 p-6">
                <p class="text-green-700 dark:text-green-300 font-medium">
                    ✓ {{ __('You accepted this offer on') }} {{ $offer->responded_at->format('F j, Y') }}
                </p>
            </div>
        @elseif($offer->status === 'rejected')
            <div class="bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800 p-6">
                <p class="text-red-700 dark:text-red-300 font-medium">
                    {{ __('You rejected this offer on') }} {{ $offer->responded_at->format('F j, Y') }}
                </p>
                @if($offer->tenant_response_notes)
                    <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ __('Reason:') }} {{ $offer->tenant_response_notes }}</p>
                @endif
            </div>
        @elseif($offer->status === 'negotiating')
            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800 p-6">
                <p class="text-yellow-700 dark:text-yellow-300 font-medium">
                    {{ __('Counter offer submitted on') }} {{ $offer->responded_at->format('F j, Y') }}
                </p>
                <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-2">
                    {{ __('Counter Offer Amount:') }} AED {{ number_format($offer->tenant_counter_offer_amount, 2) }}
                </p>
                @if($offer->tenant_counter_offer_terms)
                    <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-1">{{ $offer->tenant_counter_offer_terms }}</p>
                @endif
            </div>
        @endif
    </div>
</div>
