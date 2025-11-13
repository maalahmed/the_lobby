<div>
    <div class="mb-6">
        <div class="flex items-center">
            <a href="{{ route('admin.user-profiles.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Create User Profile') }}</h1>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form wire:submit.prevent="save">
            <!-- User Selection -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('User Account') }}</h2>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('User') }} <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="user_id" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('user_id') border-red-500 @enderror">
                            <option value="">{{ __('Select User') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Profile Type -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Profile Type') }}</h2>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Profile Type') }} <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="profile_type" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('profile_type') border-red-500 @enderror">
                            <option value="">{{ __('Select Type') }}</option>
                            <option value="admin">{{ __('Admin') }}</option>
                            <option value="landlord">{{ __('Landlord') }}</option>
                            <option value="tenant">{{ __('Tenant') }}</option>
                            <option value="service_provider">{{ __('Service Provider') }}</option>
                        </select>
                        @error('profile_type')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Personal Information') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('First Name') }}</label>
                        <input type="text" 
                               wire:model="first_name" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('first_name') border-red-500 @enderror">
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Last Name') }}</label>
                        <input type="text" 
                               wire:model="last_name" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('last_name') border-red-500 @enderror">
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Date of Birth') }}</label>
                        <input type="date" 
                               wire:model="date_of_birth" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('date_of_birth') border-red-500 @enderror">
                        @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Gender') }}</label>
                        <select wire:model="gender" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('gender') border-red-500 @enderror">
                            <option value="">{{ __('Select Gender') }}</option>
                            <option value="male">{{ __('Male') }}</option>
                            <option value="female">{{ __('Female') }}</option>
                            <option value="other">{{ __('Other') }}</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Nationality') }}</label>
                        <input type="text" 
                               wire:model="nationality" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('nationality') border-red-500 @enderror">
                        @error('nationality')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Identification -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Identification') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('National ID') }}</label>
                        <input type="text" 
                               wire:model="national_id" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('national_id') border-red-500 @enderror">
                        @error('national_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Passport Number') }}</label>
                        <input type="text" 
                               wire:model="passport_number" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('passport_number') border-red-500 @enderror">
                        @error('passport_number')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Address (JSON) -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Address') }}</h2>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Address (JSON)') }}</label>
                    <textarea wire:model="address" 
                              rows="4"
                              placeholder='{"street": "", "city": "", "state": "", "country": "", "postal_code": ""}'
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 font-mono text-sm @error('address') border-red-500 @enderror"></textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">{{ __('Enter address as JSON format') }}</p>
                </div>
            </div>

            <!-- Emergency Contact (JSON) -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Emergency Contact') }}</h2>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Emergency Contact (JSON)') }}</label>
                    <textarea wire:model="emergency_contact" 
                              rows="4"
                              placeholder='{"name": "", "relationship": "", "phone": "", "email": ""}'
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 font-mono text-sm @error('emergency_contact') border-red-500 @enderror"></textarea>
                    @error('emergency_contact')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">{{ __('Enter emergency contact as JSON format') }}</p>
                </div>
            </div>

            <!-- Preferences (JSON) -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Preferences') }}</h2>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Preferences (JSON)') }}</label>
                    <textarea wire:model="preferences" 
                              rows="4"
                              placeholder='{"language": "en", "timezone": "UTC", "notifications": true}'
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 font-mono text-sm @error('preferences') border-red-500 @enderror"></textarea>
                    @error('preferences')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">{{ __('Enter preferences as JSON format') }}</p>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('admin.user-profiles.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Create Profile') }}
                </button>
            </div>
        </form>
    </div>
</div>
