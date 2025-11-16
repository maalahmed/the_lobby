<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold ~text-gray-800">{{ __('Lease Renewals') }}</h2>
            <p class="~text-gray-600">{{ __('Manage lease renewals and expiring contracts') }}</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Expiring in 30 Days -->
            <div class="~bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm ~text-gray-600 mb-1">{{ __('Expiring in 30 Days') }}</p>
                        <h3 class="text-3xl font-bold text-red-600">{{ $stats['expiring']['expiring_30_days'] }}</h3>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Expiring in 60 Days -->
            <div class="~bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm ~text-gray-600 mb-1">{{ __('Expiring in 60 Days') }}</p>
                        <h3 class="text-3xl font-bold text-orange-600">{{ $stats['expiring']['expiring_60_days'] }}</h3>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Offers -->
            <div class="~bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm ~text-gray-600 mb-1">{{ __('Pending Offers') }}</p>
                        <h3 class="text-3xl font-bold text-blue-600">{{ $stats['offers']['pending'] }}</h3>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Accepted Offers -->
            <div class="~bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm ~text-gray-600 mb-1">{{ __('Accepted Offers') }}</p>
                        <h3 class="text-3xl font-bold text-green-600">{{ $stats['offers']['accepted'] }}</h3>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="~bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium ~text-gray-700 mb-2">{{ __('Search') }}</label>
                    <input wire:model.live="searchTerm" type="text" class="w-full ~bg-gray-50 border ~border-gray-300 ~text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="{{ __('Search tenant or unit...') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium ~text-gray-700 mb-2">{{ __('Property') }}</label>
                    <select wire:model.live="selectedProperty" class="w-full ~bg-gray-50 border ~border-gray-300 ~text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                        <option value="">{{ __('All Properties') }}</option>
                        @foreach($properties as $property)
                            <option value="{{ $property->id }}">{{ $property->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium ~text-gray-700 mb-2">{{ __('Offer Status') }}</label>
                    <select wire:model.live="selectedStatus" class="w-full ~bg-gray-50 border ~border-gray-300 ~text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                        <option value="">{{ __('All Statuses') }}</option>
                        <option value="draft">{{ __('Draft') }}</option>
                        <option value="sent">{{ __('Sent') }}</option>
                        <option value="viewed">{{ __('Viewed') }}</option>
                        <option value="accepted">{{ __('Accepted') }}</option>
                        <option value="rejected">{{ __('Rejected') }}</option>
                        <option value="negotiating">{{ __('Negotiating') }}</option>
                        <option value="expired">{{ __('Expired') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Expiring Leases Table -->
        <div class="~bg-white rounded-lg shadow-md mb-6">
            <div class="p-6 border-b ~border-gray-200">
                <h3 class="text-lg font-semibold ~text-gray-800">{{ __('Expiring Leases (Next 90 Days)') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y ~divide-gray-200">
                    <thead class="~bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium ~text-gray-500 uppercase tracking-wider">{{ __('Tenant') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium ~text-gray-500 uppercase tracking-wider">{{ __('Property / Unit') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium ~text-gray-500 uppercase tracking-wider">{{ __('Rent') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium ~text-gray-500 uppercase tracking-wider">{{ __('End Date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium ~text-gray-500 uppercase tracking-wider">{{ __('Days Remaining') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium ~text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="~bg-white divide-y ~divide-gray-200">
                        @forelse($expiringLeases as $lease)
                            @php
                                $daysRemaining = now()->diffInDays($lease->end_date, false);
                                $urgencyClass = $daysRemaining <= 30 ? 'text-red-600 font-bold' : ($daysRemaining <= 60 ? 'text-orange-600 font-semibold' : 'text-yellow-600');
                            @endphp
                            <tr class="hover:~bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium ~text-gray-900">{{ $lease->tenant->user->name }}</div>
                                    <div class="text-sm ~text-gray-500">{{ $lease->tenant->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm ~text-gray-900">{{ $lease->unit->property->name }}</div>
                                    <div class="text-sm ~text-gray-500">{{ __('Unit') }} {{ $lease->unit->unit_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm ~text-gray-900">
                                    {{ number_format($lease->rent_amount, 2) }} {{ __('AED') }}
                                    <div class="text-xs ~text-gray-500">{{ ucfirst($lease->rent_frequency) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm ~text-gray-900">
                                    {{ $lease->end_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="{{ $urgencyClass }} text-sm">
                                        {{ $daysRemaining }} {{ __('days') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">
                                        {{ __('Create Offer') }}
                                    </a>
                                    <a href="{{ route('admin.lease-contracts.show', $lease->id) }}" class="text-gray-600 hover:text-gray-900">
                                        {{ __('View Contract') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center ~text-gray-500">
                                    {{ __('No expiring leases found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t ~border-gray-200">
                {{ $expiringLeases->links() }}
            </div>
        </div>

        <!-- Recent Renewal Offers -->
        @if($renewalOffers->count() > 0)
            <div class="~bg-white rounded-lg shadow-md">
                <div class="p-6 border-b ~border-gray-200">
                    <h3 class="text-lg font-semibold ~text-gray-800">{{ __('Recent Renewal Offers') }}</h3>
                </div>
                <div class="divide-y ~divide-gray-200">
                    @foreach($renewalOffers as $offer)
                        <div class="p-6 hover:~bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h4 class="text-sm font-medium ~text-gray-900">
                                            {{ $offer->tenant->user->name }} - {{ $offer->unit->property->name }} (Unit {{ $offer->unit->unit_number }})
                                        </h4>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $offer->status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $offer->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $offer->status === 'sent' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $offer->status === 'viewed' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $offer->status === 'negotiating' ? 'bg-purple-100 text-purple-800' : '' }}
                                            {{ $offer->status === 'expired' ? 'bg-gray-100 text-gray-800' : '' }}
                                        ">
                                            {{ ucfirst($offer->status) }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-4 gap-4 text-sm ~text-gray-600">
                                        <div>
                                            <span class="font-medium">{{ __('Current Rent:') }}</span> {{ number_format($offer->current_rent_amount, 2) }} AED
                                        </div>
                                        <div>
                                            <span class="font-medium">{{ __('Proposed Rent:') }}</span> {{ number_format($offer->proposed_rent_amount, 2) }} AED
                                        </div>
                                        <div>
                                            <span class="font-medium">{{ __('Increase:') }}</span> 
                                            <span class="{{ $offer->rent_increase_percentage > 0 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $offer->rent_increase_percentage > 0 ? '+' : '' }}{{ number_format($offer->rent_increase_percentage, 1) }}%
                                            </span>
                                        </div>
                                        <div>
                                            <span class="font-medium">{{ __('Expires:') }}</span> {{ $offer->offer_expiry_date->format('M d, Y') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <a href="#" class="text-blue-600 hover:text-blue-900">
                                        {{ __('View Details') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
