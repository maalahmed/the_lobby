<div>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Service Provider Details') }}</h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.service-providers.edit', $provider) }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('admin.service-providers.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Company Information -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">{{ __('Company Information') }}</h2>
                </div>
                <div class="px-6 py-4 space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500">{{ __('Provider Code:') }}</span>
                        <span class="text-sm text-gray-900 ml-2">{{ $provider->provider_code }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">{{ __('Company Name:') }}</span>
                        <span class="text-sm text-gray-900 ml-2">{{ $provider->company_name }}</span>
                    </div>
                    @if($provider->business_registration)
                        <div>
                            <span class="text-sm font-medium text-gray-500">{{ __('Business Registration:') }}</span>
                            <span class="text-sm text-gray-900 ml-2">{{ $provider->business_registration }}</span>
                        </div>
                    @endif
                    @if($provider->tax_number)
                        <div>
                            <span class="text-sm font-medium text-gray-500">{{ __('Tax Number:') }}</span>
                            <span class="text-sm text-gray-900 ml-2">{{ $provider->tax_number }}</span>
                        </div>
                    @endif
                    @if($provider->years_in_business)
                        <div>
                            <span class="text-sm font-medium text-gray-500">{{ __('Years in Business:') }}</span>
                            <span class="text-sm text-gray-900 ml-2">{{ $provider->years_in_business }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">{{ __('Contact Information') }}</h2>
                </div>
                <div class="px-6 py-4 space-y-3">
                    @if($provider->primary_contact_name)
                        <div>
                            <span class="text-sm font-medium text-gray-500">{{ __('Contact Name:') }}</span>
                            <span class="text-sm text-gray-900 ml-2">{{ $provider->primary_contact_name }}</span>
                        </div>
                    @endif
                    @if($provider->primary_contact_phone)
                        <div>
                            <span class="text-sm font-medium text-gray-500">{{ __('Phone:') }}</span>
                            <span class="text-sm text-gray-900 ml-2">{{ $provider->primary_contact_phone }}</span>
                        </div>
                    @endif
                    @if($provider->primary_contact_email)
                        <div>
                            <span class="text-sm font-medium text-gray-500">{{ __('Email:') }}</span>
                            <span class="text-sm text-gray-900 ml-2">{{ $provider->primary_contact_email }}</span>
                        </div>
                    @endif
                    @if($provider->office_address)
                        <div>
                            <span class="text-sm font-medium text-gray-500">{{ __('Office Address:') }}</span>
                            <div class="text-sm text-gray-900 mt-1">
                                @foreach($provider->office_address as $key => $value)
                                    <div>{{ ucfirst($key) }}: {{ $value }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Services -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">{{ __('Services') }}</h2>
                </div>
                <div class="px-6 py-4 space-y-3">
                    @if($provider->service_categories)
                        <div>
                            <span class="text-sm font-medium text-gray-500 block mb-2">{{ __('Service Categories:') }}</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach($provider->service_categories as $category)
                                    <span class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">{{ $category }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if($provider->specializations)
                        <div>
                            <span class="text-sm font-medium text-gray-500 block mb-2">{{ __('Specializations:') }}</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach($provider->specializations as $spec)
                                    <span class="px-3 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">{{ $spec }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if($provider->service_areas)
                        <div>
                            <span class="text-sm font-medium text-gray-500 block mb-2">{{ __('Service Areas:') }}</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach($provider->service_areas as $area)
                                    <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">{{ $area }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if($provider->emergency_services)
                        <div>
                            <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">{{ __('Emergency Services Available') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Notes -->
            @if($provider->notes)
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">{{ __('Notes') }}</h2>
                    </div>
                    <div class="px-6 py-4">
                        <p class="text-sm text-gray-700">{{ $provider->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status & Performance -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">{{ __('Status & Performance') }}</h2>
                </div>
                <div class="px-6 py-4 space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500">{{ __('Status:') }}</span>
                        <div class="mt-1">
                            @if($provider->status === 'active')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ __('Active') }}
                                </span>
                            @elseif($provider->status === 'inactive')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ __('Inactive') }}
                                </span>
                            @elseif($provider->status === 'suspended')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    {{ __('Suspended') }}
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    {{ __('Blacklisted') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">{{ __('Rating:') }}</span>
                        <div class="flex items-center mt-1">
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-sm text-gray-900 ml-2">{{ number_format($provider->rating, 2) }}</span>
                        </div>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">{{ __('Total Jobs:') }}</span>
                        <span class="text-sm text-gray-900 ml-2">{{ $provider->total_jobs }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">{{ __('Completed Jobs:') }}</span>
                        <span class="text-sm text-gray-900 ml-2">{{ $provider->completed_jobs }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">{{ __('Cancelled Jobs:') }}</span>
                        <span class="text-sm text-gray-900 ml-2">{{ $provider->cancelled_jobs }}</span>
                    </div>
                </div>
            </div>

            <!-- Business Details -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">{{ __('Business Details') }}</h2>
                </div>
                <div class="px-6 py-4 space-y-3">
                    @if($provider->team_size)
                        <div>
                            <span class="text-sm font-medium text-gray-500">{{ __('Team Size:') }}</span>
                            <span class="text-sm text-gray-900 ml-2">{{ $provider->team_size }}</span>
                        </div>
                    @endif
                    @if($provider->payment_terms)
                        <div>
                            <span class="text-sm font-medium text-gray-500">{{ __('Payment Terms:') }}</span>
                            <span class="text-sm text-gray-900 ml-2">{{ $provider->payment_terms }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- User Association -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">{{ __('Associated User') }}</h2>
                </div>
                <div class="px-6 py-4 space-y-2">
                    <div>
                        <span class="text-sm font-medium text-gray-500">{{ __('Name:') }}</span>
                        <span class="text-sm text-gray-900 ml-2">{{ $provider->user->name }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">{{ __('Email:') }}</span>
                        <span class="text-sm text-gray-900 ml-2">{{ $provider->user->email }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">{{ __('Actions') }}</h2>
                </div>
                <div class="px-6 py-4">
                    <button wire:click="delete" 
                            wire:confirm="Are you sure you want to delete this service provider?"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Delete Service Provider') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
