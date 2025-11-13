<div>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('admin.user-profiles.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-2xl font-semibold text-gray-900">{{ __('User Profile Details') }}</h1>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.user-profiles.edit', $profile) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Edit') }}
                </a>
                <button wire:click="delete" 
                        wire:confirm="Are you sure you want to delete this profile?"
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Delete') }}
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- User Account -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('User Account') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Name') }}</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $profile->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Email') }}</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $profile->user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Personal Information') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('First Name') }}</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $profile->first_name ?: 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Last Name') }}</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $profile->last_name ?: 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Date of Birth') }}</p>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $profile->date_of_birth ? $profile->date_of_birth->format('Y-m-d') : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Gender') }}</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $profile->gender ? ucfirst($profile->gender) : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Nationality') }}</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $profile->nationality ?: 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Identification -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Identification') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('National ID') }}</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $profile->national_id ?: 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Passport Number') }}</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $profile->passport_number ?: 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Address') }}</h2>
                @if($profile->address)
                    <div class="bg-gray-50 rounded p-4">
                        <pre class="text-sm text-gray-900 whitespace-pre-wrap font-mono">{{ json_encode($profile->address, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                @else
                    <p class="text-sm text-gray-500">{{ __('No address provided') }}</p>
                @endif
            </div>

            <!-- Emergency Contact -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Emergency Contact') }}</h2>
                @if($profile->emergency_contact)
                    <div class="bg-gray-50 rounded p-4">
                        <pre class="text-sm text-gray-900 whitespace-pre-wrap font-mono">{{ json_encode($profile->emergency_contact, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                @else
                    <p class="text-sm text-gray-500">{{ __('No emergency contact provided') }}</p>
                @endif
            </div>

            <!-- Preferences -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Preferences') }}</h2>
                @if($profile->preferences)
                    <div class="bg-gray-50 rounded p-4">
                        <pre class="text-sm text-gray-900 whitespace-pre-wrap font-mono">{{ json_encode($profile->preferences, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                @else
                    <p class="text-sm text-gray-500">{{ __('No preferences set') }}</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Profile Type -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Profile Type') }}</h2>
                <div>
                    @if($profile->profile_type === 'admin')
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                            {{ __('Admin') }}
                        </span>
                    @elseif($profile->profile_type === 'landlord')
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ __('Landlord') }}
                        </span>
                    @elseif($profile->profile_type === 'tenant')
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            {{ __('Tenant') }}
                        </span>
                    @else
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            {{ __('Service Provider') }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Timestamps -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Timeline') }}</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Created') }}</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $profile->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Last Updated') }}</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $profile->updated_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
