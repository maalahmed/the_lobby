<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('Create Property') }}</h1>
                <p class="mt-1 text-sm text-gray-600">{{ __('Add a new property to the system') }}</p>
            </div>
            <a href="{{ route('admin.properties.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('Back to List') }}
            </a>
        </div>
    </div>

    <!-- Form -->
    <form wire:submit="save" class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Basic Information') }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Property Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Property Name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           wire:model="name" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Landlord -->
                <div>
                    <label for="landlord_id" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Landlord') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="landlord_id" 
                            wire:model="landlord_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('landlord_id') border-red-500 @enderror">
                        <option value="">{{ __('Select Landlord') }}</option>
                        @foreach($landlords as $landlord)
                            <option value="{{ $landlord->id }}">{{ $landlord->name }} - {{ $landlord->email }}</option>
                        @endforeach
                    </select>
                    @error('landlord_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Property Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Property Type') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="type" 
                            wire:model="type" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('type') border-red-500 @enderror">
                        <option value="residential">{{ __('Residential') }}</option>
                        <option value="commercial">{{ __('Commercial') }}</option>
                        <option value="mixed">{{ __('Mixed') }}</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Total Units -->
                <div>
                    <label for="total_units" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Total Units') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="total_units" 
                           wire:model="total_units" 
                           min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('total_units') border-red-500 @enderror">
                    @error('total_units')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Year Built -->
                <div>
                    <label for="year_built" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Year Built') }}
                    </label>
                    <input type="number" 
                           id="year_built" 
                           wire:model="year_built" 
                           min="1900"
                           max="{{ date('Y') + 1 }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('year_built') border-red-500 @enderror">
                    @error('year_built')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Status') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="status" 
                            wire:model="status" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
                        <option value="active">{{ __('Active') }}</option>
                        <option value="inactive">{{ __('Inactive') }}</option>
                        <option value="maintenance">{{ __('Under Maintenance') }}</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Description') }}
                    </label>
                    <textarea id="description" 
                              wire:model="description" 
                              rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"></textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Location Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Location') }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Address') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="address" 
                           wire:model="address" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror">
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- City -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('City') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="city" 
                           wire:model="city" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('city') border-red-500 @enderror">
                    @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- State -->
                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('State/Province') }}
                    </label>
                    <input type="text" 
                           id="state" 
                           wire:model="state" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('state') border-red-500 @enderror">
                    @error('state')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Postal Code -->
                <div>
                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Postal Code') }}
                    </label>
                    <input type="text" 
                           id="postal_code" 
                           wire:model="postal_code" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('postal_code') border-red-500 @enderror">
                    @error('postal_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Country -->
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Country') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="country" 
                           wire:model="country" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('country') border-red-500 @enderror">
                    @error('country')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}">
            <a href="{{ route('admin.properties.index') }}" 
               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                {{ __('Cancel') }}
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                {{ __('Create Property') }}
            </button>
        </div>
    </form>
</div>
