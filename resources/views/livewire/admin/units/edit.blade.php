<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">{{ __('Edit Unit') }}</h2>
                        <a href="{{ route('admin.units.show', $unit->id) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Back to Unit') }}
                        </a>
                    </div>

                    @if (session()->has('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form wire:submit="update">
                        <!-- Property Selection -->
                        <div class="mb-4">
                            <label for="property_id" class="block text-sm font-medium text-gray-700">{{ __('Property') }} <span class="text-red-500">*</span></label>
                            <select wire:model="property_id" id="property_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ __('Select Property') }}</option>
                                @foreach($properties as $property)
                                    <option value="{{ $property->id }}">{{ $property->name }}</option>
                                @endforeach
                            </select>
                            @error('property_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Unit Number & Floor -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="unit_number" class="block text-sm font-medium text-gray-700">{{ __('Unit Number') }} <span class="text-red-500">*</span></label>
                                <input wire:model="unit_number" type="text" id="unit_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('unit_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="floor" class="block text-sm font-medium text-gray-700">{{ __('Floor') }}</label>
                                <input wire:model="floor" type="number" id="floor" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('floor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Unit Type & Area -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">{{ __('Unit Type') }} <span class="text-red-500">*</span></label>
                                <select wire:model="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="studio">{{ __('Studio') }}</option>
                                    <option value="1br">{{ __('1 Bedroom') }}</option>
                                    <option value="2br">{{ __('2 Bedrooms') }}</option>
                                    <option value="3br">{{ __('3 Bedrooms') }}</option>
                                    <option value="4br">{{ __('4 Bedrooms') }}</option>
                                    <option value="5br+">{{ __('5+ Bedrooms') }}</option>
                                    <option value="penthouse">{{ __('Penthouse') }}</option>
                                    <option value="office">{{ __('Office') }}</option>
                                    <option value="retail">{{ __('Retail') }}</option>
                                    <option value="warehouse">{{ __('Warehouse') }}</option>
                                </select>
                                @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="area" class="block text-sm font-medium text-gray-700">{{ __('Area (sq ft)') }} <span class="text-red-500">*</span></label>
                                <input wire:model="area" type="number" step="0.01" id="area" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('area') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Bedrooms, Bathrooms, Balconies, Parking -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            <div>
                                <label for="bedrooms" class="block text-sm font-medium text-gray-700">{{ __('Bedrooms') }} <span class="text-red-500">*</span></label>
                                <input wire:model="bedrooms" type="number" id="bedrooms" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('bedrooms') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="bathrooms" class="block text-sm font-medium text-gray-700">{{ __('Bathrooms') }} <span class="text-red-500">*</span></label>
                                <input wire:model="bathrooms" type="number" id="bathrooms" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('bathrooms') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="balconies" class="block text-sm font-medium text-gray-700">{{ __('Balconies') }}</label>
                                <input wire:model="balconies" type="number" id="balconies" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('balconies') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="parking_spaces" class="block text-sm font-medium text-gray-700">{{ __('Parking') }}</label>
                                <input wire:model="parking_spaces" type="number" id="parking_spaces" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('parking_spaces') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Rent Amount & Security Deposit -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="rent_amount" class="block text-sm font-medium text-gray-700">{{ __('Rent Amount') }} <span class="text-red-500">*</span></label>
                                <input wire:model="rent_amount" type="number" step="0.01" id="rent_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('rent_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="security_deposit" class="block text-sm font-medium text-gray-700">{{ __('Security Deposit') }}</label>
                                <input wire:model="security_deposit" type="number" step="0.01" id="security_deposit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('security_deposit') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Rent Frequency & Furnished Status -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="rent_frequency" class="block text-sm font-medium text-gray-700">{{ __('Rent Frequency') }} <span class="text-red-500">*</span></label>
                                <select wire:model="rent_frequency" id="rent_frequency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="monthly">{{ __('Monthly') }}</option>
                                    <option value="quarterly">{{ __('Quarterly') }}</option>
                                    <option value="semi_annual">{{ __('Semi-Annual') }}</option>
                                    <option value="annual">{{ __('Annual') }}</option>
                                </select>
                                @error('rent_frequency') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="furnished" class="block text-sm font-medium text-gray-700">{{ __('Furnished Status') }} <span class="text-red-500">*</span></label>
                                <select wire:model="furnished" id="furnished" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="unfurnished">{{ __('Unfurnished') }}</option>
                                    <option value="semi_furnished">{{ __('Semi-Furnished') }}</option>
                                    <option value="fully_furnished">{{ __('Fully Furnished') }}</option>
                                </select>
                                @error('furnished') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Status & Available From -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Status') }} <span class="text-red-500">*</span></label>
                                <select wire:model="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="available">{{ __('Available') }}</option>
                                    <option value="occupied">{{ __('Occupied') }}</option>
                                    <option value="maintenance">{{ __('Maintenance') }}</option>
                                    <option value="reserved">{{ __('Reserved') }}</option>
                                </select>
                                @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="available_from" class="block text-sm font-medium text-gray-700">{{ __('Available From') }}</label>
                                <input wire:model="available_from" type="date" id="available_from" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('available_from') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                {{ __('Update Unit') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
        </div>
</div>
