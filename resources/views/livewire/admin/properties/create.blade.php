<div class="p-6">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Flash Messages -->
        @if (session()->has('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-red-800 mb-2">{{ __('Error') }}</h3>
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-green-800 mb-2">{{ __('Success') }}</h3>
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Validation Errors Summary -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-red-800 mb-2">{{ __('Please fix the following errors:') }}</h3>
                <ul class="list-disc list-inside text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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
                            <option value="{{ $landlord->id }}">{{ $landlord->first_name }} {{ $landlord->last_name }} - {{ $landlord->email }}</option>
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
                        <option value="industrial">{{ __('Industrial') }}</option>
                        <option value="villa">{{ __('Villa') }}</option>
                        <option value="building">{{ __('Building') }}</option>
                        <option value="apartment">{{ __('Apartment') }}</option>
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
                        <option value="under_maintenance">{{ __('Under Maintenance') }}</option>
                        <option value="vacant">{{ __('Vacant') }}</option>
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
                        {{ __('State/Province') }} <span class="text-red-500">*</span>
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
                    wire:loading.attr="disabled"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50">
                <span wire:loading.remove>{{ __('Create Property') }}</span>
                <span wire:loading>{{ __('Creating...') }}</span>
            </button>
        </div>
    </form>

    @push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            console.log('=== PROPERTY CREATE FORM INITIALIZED ===');
            console.log('Livewire version:', window.Livewire.version);
            
            // Track all Livewire updates
            Livewire.hook('component.init', ({ component }) => {
                console.log('Livewire component initialized:', component.name);
            });
            
            Livewire.hook('message.sent', (message, component) => {
                console.log('=== LIVEWIRE MESSAGE SENT ===', {
                    componentName: component.name,
                    updateQueue: message.updateQueue,
                    payload: message.payload
                });
            });
            
            Livewire.hook('message.received', (message, component) => {
                console.log('=== LIVEWIRE MESSAGE RECEIVED ===', {
                    componentName: component.name,
                    response: message.response
                });
            });
            
            Livewire.hook('message.failed', ({ message, component }) => {
                console.error('=== LIVEWIRE MESSAGE FAILED ===', {
                    componentName: component.name,
                    message: message
                });
            });
            
            Livewire.on('property-created', () => {
                console.log('✅ Property created event received');
            });
        });
        
        // Log form submission attempts
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded');
            
            const form = document.querySelector('form');
            if (form) {
                console.log('Form element found:', form);
                
                form.addEventListener('submit', function(e) {
                    console.log('=== FORM SUBMIT EVENT ===');
                    console.log('Event type:', e.type);
                    console.log('Default prevented:', e.defaultPrevented);
                    
                    const formData = new FormData(form);
                    const formObj = {};
                    formData.forEach((value, key) => {
                        formObj[key] = value;
                    });
                    console.log('Form data object:', formObj);
                });
                
                // Track wire:model bindings
                const wireModelInputs = form.querySelectorAll('[wire\\:model]');
                console.log(`Found ${wireModelInputs.length} wire:model bindings`);
                wireModelInputs.forEach(input => {
                    console.log(`- ${input.getAttribute('wire:model')}: ${input.type}`);
                });
            } else {
                console.error('Form element not found!');
            }
        });
        
        // Catch all Livewire errors
        window.addEventListener('livewire:exception', (event) => {
            console.error('=== LIVEWIRE EXCEPTION ===', event.detail);
        });
        
        // Track Livewire loading states
        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', ({ el, component }) => {
                console.log('Element updated:', el);
            });
        });
    </script>
    @endpush
    </div>
        </div>
</div>
