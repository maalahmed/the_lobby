<div>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">{{ __('Add Service Provider') }}</h1>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <form wire:submit="save">
            <div class="p-6 space-y-6">
                <!-- User Association -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('User Association') }}</h3>
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700">
                            {{ __('Associated User') }} <span class="text-red-500">*</span>
                        </label>
                        <select id="user_id" wire:model="user_id"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ __('Select User') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @error('user_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Company Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Company Information') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700">
                                {{ __('Company Name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="company_name" wire:model="company_name"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('company_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="business_registration" class="block text-sm font-medium text-gray-700">
                                {{ __('Business Registration') }}
                            </label>
                            <input type="text" id="business_registration" wire:model="business_registration"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('business_registration') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="tax_number" class="block text-sm font-medium text-gray-700">
                                {{ __('Tax Number') }}
                            </label>
                            <input type="text" id="tax_number" wire:model="tax_number"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('tax_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="years_in_business" class="block text-sm font-medium text-gray-700">
                                {{ __('Years in Business') }}
                            </label>
                            <input type="number" id="years_in_business" wire:model="years_in_business" min="0"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('years_in_business') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Contact Information') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="primary_contact_name" class="block text-sm font-medium text-gray-700">
                                {{ __('Contact Name') }}
                            </label>
                            <input type="text" id="primary_contact_name" wire:model="primary_contact_name"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('primary_contact_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="primary_contact_phone" class="block text-sm font-medium text-gray-700">
                                {{ __('Contact Phone') }}
                            </label>
                            <input type="text" id="primary_contact_phone" wire:model="primary_contact_phone"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('primary_contact_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="primary_contact_email" class="block text-sm font-medium text-gray-700">
                                {{ __('Contact Email') }}
                            </label>
                            <input type="email" id="primary_contact_email" wire:model="primary_contact_email"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('primary_contact_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="office_address" class="block text-sm font-medium text-gray-700">
                                {{ __('Office Address (JSON format)') }}
                            </label>
                            <textarea id="office_address" wire:model="office_address" rows="2"
                                      placeholder='{"street": "123 Main St", "city": "Dubai", "country": "UAE"}'
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                            @error('office_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Services -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Services') }}</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="service_categories" class="block text-sm font-medium text-gray-700">
                                {{ __('Service Categories (comma-separated)') }}
                            </label>
                            <input type="text" id="service_categories" wire:model="service_categories"
                                   placeholder="Plumbing, Electrical, HVAC"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('service_categories') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="specializations" class="block text-sm font-medium text-gray-700">
                                {{ __('Specializations (comma-separated)') }}
                            </label>
                            <input type="text" id="specializations" wire:model="specializations"
                                   placeholder="Commercial, Residential, Emergency"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('specializations') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="service_areas" class="block text-sm font-medium text-gray-700">
                                {{ __('Service Areas (comma-separated)') }}
                            </label>
                            <input type="text" id="service_areas" wire:model="service_areas"
                                   placeholder="Dubai, Abu Dhabi, Sharjah"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('service_areas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Business Details -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Business Details') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="team_size" class="block text-sm font-medium text-gray-700">
                                {{ __('Team Size') }}
                            </label>
                            <input type="number" id="team_size" wire:model="team_size" min="0"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('team_size') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="payment_terms" class="block text-sm font-medium text-gray-700">
                                {{ __('Payment Terms') }}
                            </label>
                            <input type="text" id="payment_terms" wire:model="payment_terms"
                                   placeholder="Net 30, COD, etc."
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('payment_terms') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="emergency_services" wire:model="emergency_services"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="emergency_services" class="ml-2 block text-sm text-gray-700">
                                {{ __('Provides Emergency Services') }}
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Status & Notes -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Status & Notes') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">
                                {{ __('Status') }} <span class="text-red-500">*</span>
                            </label>
                            <select id="status" wire:model="status"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="active">{{ __('Active') }}</option>
                                <option value="inactive">{{ __('Inactive') }}</option>
                                <option value="suspended">{{ __('Suspended') }}</option>
                                <option value="blacklisted">{{ __('Blacklisted') }}</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700">
                                {{ __('Notes') }}
                            </label>
                            <textarea id="notes" wire:model="notes" rows="3"
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                            @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('admin.service-providers.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Cancel') }}
                </a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Create Service Provider') }}
                </button>
            </div>
        </form>
    </div>
</div>
