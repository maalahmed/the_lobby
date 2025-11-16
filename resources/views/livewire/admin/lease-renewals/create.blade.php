<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold ~text-gray-800">{{ __('Create Renewal Offer') }}</h2>
                <p class="~text-gray-600">{{ __('Generate a lease renewal offer for') }} {{ $lease->tenant->user->name }}</p>
            </div>
            <a href="{{ route('admin.lease-renewals.index') }}" class="text-blue-600 hover:text-blue-800">
                ← {{ __('Back to Renewals') }}
            </a>
        </div>

        <!-- Current Lease Info -->
        <div class="~bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold ~text-gray-800 mb-4">{{ __('Current Lease Information') }}</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm ~text-gray-600">{{ __('Property') }}</p>
                    <p class="font-semibold ~text-gray-900">{{ $lease->unit->property->name }}</p>
                </div>
                <div>
                    <p class="text-sm ~text-gray-600">{{ __('Unit') }}</p>
                    <p class="font-semibold ~text-gray-900">{{ $lease->unit->unit_number }}</p>
                </div>
                <div>
                    <p class="text-sm ~text-gray-600">{{ __('Current Rent') }}</p>
                    <p class="font-semibold ~text-gray-900">{{ number_format($lease->rent_amount, 2) }} AED</p>
                </div>
                <div>
                    <p class="text-sm ~text-gray-600">{{ __('Lease Ends') }}</p>
                    <p class="font-semibold ~text-gray-900">{{ $lease->end_date->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Renewal Offer Form -->
        <form wire:submit.prevent="sendOffer">
            <div class="~bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold ~text-gray-800 mb-4">{{ __('Proposed Terms') }}</h3>

                <!-- Rent Amount -->
                <div class="mb-6">
                    <label class="block text-sm font-medium ~text-gray-700 mb-2">{{ __('Proposed Rent Amount (AED)') }}</label>
                    <input wire:model.live="proposed_rent_amount" type="number" step="0.01" class="w-full ~bg-gray-50 border ~border-gray-300 ~text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                    @error('proposed_rent_amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                    @if($rent_increase_percentage != 0)
                        <p class="mt-2 text-sm {{ $rent_increase_percentage > 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ $rent_increase_percentage > 0 ? '+' : '' }}{{ number_format($rent_increase_percentage, 2) }}%
                            ({{ $rent_increase_percentage > 0 ? '+' : '' }}{{ number_format($rent_difference, 2) }} AED)
                        </p>
                    @endif
                </div>

                <!-- Lease Duration and Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium ~text-gray-700 mb-2">{{ __('Lease Duration (Months)') }}</label>
                        <input wire:model.live="proposed_lease_duration" type="number" min="1" max="60" class="w-full ~bg-gray-50 border ~border-gray-300 ~text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                        @error('proposed_lease_duration') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium ~text-gray-700 mb-2">{{ __('Start Date') }}</label>
                        <input wire:model.live="proposed_start_date" type="date" class="w-full ~bg-gray-50 border ~border-gray-300 ~text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                        @error('proposed_start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium ~text-gray-700 mb-2">{{ __('End Date') }}</label>
                    <input wire:model="proposed_end_date" type="date" class="w-full ~bg-gray-50 border ~border-gray-300 ~text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" readonly>
                    <p class="mt-1 text-sm ~text-gray-500">{{ __('Automatically calculated based on duration') }}</p>
                </div>

                <!-- Offer Expiry -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium ~text-gray-700 mb-2">{{ __('Offer Valid For (Days)') }}</label>
                        <input wire:model.live="offer_valid_days" type="number" min="1" max="90" class="w-full ~bg-gray-50 border ~border-gray-300 ~text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                    </div>

                    <div>
                        <label class="block text-sm font-medium ~text-gray-700 mb-2">{{ __('Offer Expiry Date') }}</label>
                        <input wire:model="offer_expiry_date" type="date" class="w-full ~bg-gray-50 border ~border-gray-300 ~text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                        @error('offer_expiry_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="mb-6">
                    <label class="block text-sm font-medium ~text-gray-700 mb-2">{{ __('Terms and Conditions') }}</label>
                    <textarea wire:model="terms_and_conditions" rows="6" class="w-full ~bg-gray-50 border ~border-gray-300 ~text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"></textarea>
                    @error('terms_and_conditions') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label class="block text-sm font-medium ~text-gray-700 mb-2">{{ __('Internal Notes') }}</label>
                    <textarea wire:model="notes" rows="3" placeholder="{{ __('Internal notes (not visible to tenant)') }}" class="w-full ~bg-gray-50 border ~border-gray-300 ~text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"></textarea>
                    @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Summary Card -->
            <div class="~bg-blue-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold ~text-gray-800 mb-4">{{ __('Offer Summary') }}</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm ~text-gray-600">{{ __('New Rent') }}</p>
                        <p class="text-xl font-bold ~text-gray-900">{{ number_format($proposed_rent_amount, 2) }} AED</p>
                    </div>
                    <div>
                        <p class="text-sm ~text-gray-600">{{ __('Duration') }}</p>
                        <p class="text-xl font-bold ~text-gray-900">{{ $proposed_lease_duration }} {{ __('months') }}</p>
                    </div>
                    <div>
                        <p class="text-sm ~text-gray-600">{{ __('Total Value') }}</p>
                        <p class="text-xl font-bold ~text-gray-900">{{ number_format($proposed_rent_amount * $proposed_lease_duration, 2) }} AED</p>
                    </div>
                    <div>
                        <p class="text-sm ~text-gray-600">{{ __('Valid Until') }}</p>
                        <p class="text-xl font-bold ~text-gray-900">{{ \Carbon\Carbon::parse($offer_expiry_date)->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-4">
                <button wire:click.prevent="saveDraft" type="button" class="px-6 py-3 ~bg-gray-200 ~text-gray-800 rounded-lg hover:~bg-gray-300 font-medium transition-colors">
                    {{ __('Save as Draft') }}
                </button>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors">
                    {{ __('Send Offer to Tenant') }}
                </button>
                <a href="{{ route('admin.lease-renewals.index') }}" class="px-6 py-3 ~text-gray-600 hover:~text-gray-800">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
