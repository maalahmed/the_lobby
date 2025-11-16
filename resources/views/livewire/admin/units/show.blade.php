<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">{{ __('Unit Details') }}</h2>
                        <div class="space-x-2">
                            <a href="{{ route('admin.units.edit', $unit->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Edit') }}
                            </a>
                            <button wire:click="$set('showDeleteModal', true)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Delete') }}
                            </button>
                            <a href="{{ route('admin.units.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Back to Units') }}
                            </a>
                        </div>
                    </div>

                    @if (session()->has('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Unit Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Basic Information') }}</h3>
                                
                                <div class="mb-3">
                                    <label class="text-sm font-medium text-gray-500">{{ __('Property') }}</label>
                                    <p class="text-gray-900">
                                        <a href="{{ route('admin.properties.show', $unit->property->id) }}" class="text-blue-600 hover:underline">
                                            {{ $unit->property->name }}
                                        </a>
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <label class="text-sm font-medium text-gray-500">{{ __('Unit Number') }}</label>
                                    <p class="text-gray-900">{{ $unit->unit_number }}</p>
                                </div>

                                @if($unit->floor)
                                <div class="mb-3">
                                    <label class="text-sm font-medium text-gray-500">{{ __('Floor') }}</label>
                                    <p class="text-gray-900">{{ $unit->floor }}</p>
                                </div>
                                @endif

                                <div class="mb-3">
                                    <label class="text-sm font-medium text-gray-500">{{ __('Unit Type') }}</label>
                                    <p class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $unit->type)) }}</p>
                                </div>

                                <div class="mb-3">
                                    <label class="text-sm font-medium text-gray-500">{{ __('Area') }}</label>
                                    <p class="text-gray-900">{{ number_format($unit->area, 2) }} sq ft</p>
                                </div>

                                <div class="mb-3">
                                    <label class="text-sm font-medium text-gray-500">{{ __('Status') }}</label>
                                    <p>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($unit->status === 'available') bg-green-100 text-green-800
                                            @elseif($unit->status === 'occupied') bg-blue-100 text-blue-800
                                            @elseif($unit->status === 'maintenance') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($unit->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Unit Features') }}</h3>
                                
                                <div class="mb-3">
                                    <label class="text-sm font-medium text-gray-500">{{ __('Bedrooms') }}</label>
                                    <p class="text-gray-900">{{ $unit->bedrooms }}</p>
                                </div>

                                <div class="mb-3">
                                    <label class="text-sm font-medium text-gray-500">{{ __('Bathrooms') }}</label>
                                    <p class="text-gray-900">{{ $unit->bathrooms }}</p>
                                </div>

                                @if($unit->balconies)
                                <div class="mb-3">
                                    <label class="text-sm font-medium text-gray-500">{{ __('Balconies') }}</label>
                                    <p class="text-gray-900">{{ $unit->balconies }}</p>
                                </div>
                                @endif

                                @if($unit->parking_spaces)
                                <div class="mb-3">
                                    <label class="text-sm font-medium text-gray-500">{{ __('Parking Spaces') }}</label>
                                    <p class="text-gray-900">{{ $unit->parking_spaces }}</p>
                                </div>
                                @endif

                                <div class="mb-3">
                                    <label class="text-sm font-medium text-gray-500">{{ __('Furnished Status') }}</label>
                                    <p class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $unit->furnished)) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rent Information -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Rent Information') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">{{ __('Rent Amount') }}</label>
                                <p class="text-gray-900 text-xl font-bold">{{ number_format($unit->rent_amount, 2) }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-500">{{ __('Rent Frequency') }}</label>
                                <p class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $unit->rent_frequency)) }}</p>
                            </div>

                            @if($unit->security_deposit)
                            <div>
                                <label class="text-sm font-medium text-gray-500">{{ __('Security Deposit') }}</label>
                                <p class="text-gray-900">{{ number_format($unit->security_deposit, 2) }}</p>
                            </div>
                            @endif
                        </div>

                        @if($unit->available_from)
                        <div class="mt-4">
                            <label class="text-sm font-medium text-gray-500">{{ __('Available From') }}</label>
                            <p class="text-gray-900">{{ $unit->available_from->format('M d, Y') }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Current Lease Information -->
                    @if($unit->currentLease)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Current Lease') }}</h3>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">{{ __('Tenant') }}</label>
                                    <p class="text-gray-900">{{ $unit->currentLease->tenant->name ?? __('N/A') }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">{{ __('Lease Period') }}</label>
                                    <p class="text-gray-900">
                                        {{ $unit->currentLease->start_date->format('M d, Y') }} - 
                                        {{ $unit->currentLease->end_date->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Timestamps -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-500">
                            <div>
                                <label class="font-medium">{{ __('Created') }}</label>
                                <p>{{ $unit->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                            <div>
                                <label class="font-medium">{{ __('Last Updated') }}</label>
                                <p>{{ $unit->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    {{ __('Delete Unit') }}
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        {{ __('Are you sure you want to delete this unit? This action cannot be undone.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="delete" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('Delete') }}
                        </button>
                        <button wire:click="$set('showDeleteModal', false)" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    </div>
        </div>
</div>
