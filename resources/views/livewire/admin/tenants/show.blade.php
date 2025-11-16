<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center space-x-4">
                            <h2 class="text-2xl font-bold text-gray-800">{{ $tenant->user->name }}</h2>
                            @if($tenant->status === 'active')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">{{ __('Active') }}</span>
                            @elseif($tenant->status === 'prospect')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ __('Prospect') }}</span>
                            @elseif($tenant->status === 'blacklisted')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">{{ __('Blacklisted') }}</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ __('Inactive') }}</span>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.tenants.edit', $tenant) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Edit') }}
                            </a>
                            <button wire:click="$set('showDeleteModal', true)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Delete') }}
                            </button>
                            <a href="{{ route('admin.tenants.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Back') }}
                            </a>
                        </div>
                    </div>

                    @if (session()->has('message'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('message') }}</span>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Basic Information') }}</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Tenant Code') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $tenant->tenant_code }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Email') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $tenant->user->email }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Phone') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $tenant->user->phone ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Status') }}:</span>
                                    <span class="text-sm text-gray-900 capitalize">{{ $tenant->status }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Employment Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Employment Information') }}</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Occupation') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $tenant->occupation ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Employer') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $tenant->employer ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Monthly Income') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $tenant->monthly_income ? number_format($tenant->monthly_income, 2) : 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Credit & Background -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Credit & Background') }}</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Credit Score') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $tenant->credit_score ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Background Check') }}:</span>
                                    <span class="text-sm text-gray-900 capitalize">{{ str_replace('_', ' ', $tenant->background_check_status) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        @if($tenant->emergency_contact)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Emergency Contact') }}</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Name') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $tenant->emergency_contact['name'] ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Phone') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $tenant->emergency_contact['phone'] ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Relationship') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $tenant->emergency_contact['relationship'] ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- References -->
                    @if($tenant->references && count($tenant->references) > 0)
                    <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">{{ __('References') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($tenant->references as $index => $reference)
                            <div class="bg-white p-4 rounded border border-gray-200">
                                <h4 class="text-sm font-semibold mb-2">{{ __('Reference') }} {{ $index + 1 }}</h4>
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-xs font-medium text-gray-600">{{ __('Name') }}:</span>
                                        <span class="text-xs text-gray-900">{{ $reference['name'] ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-xs font-medium text-gray-600">{{ __('Phone') }}:</span>
                                        <span class="text-xs text-gray-900">{{ $reference['phone'] ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-xs font-medium text-gray-600">{{ __('Relationship') }}:</span>
                                        <span class="text-xs text-gray-900">{{ $reference['relationship'] ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Current Lease -->
                    @if($tenant->currentLease)
                    <div class="mt-6 bg-blue-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Current Lease') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <span class="text-sm font-medium text-gray-600">{{ __('Contract Code') }}:</span>
                                <span class="text-sm text-gray-900">{{ $tenant->currentLease->contract_code }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-600">{{ __('Start Date') }}:</span>
                                <span class="text-sm text-gray-900">{{ $tenant->currentLease->start_date->format('Y-m-d') }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-600">{{ __('End Date') }}:</span>
                                <span class="text-sm text-gray-900">{{ $tenant->currentLease->end_date->format('Y-m-d') }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-600">{{ __('Monthly Rent') }}:</span>
                                <span class="text-sm text-gray-900">{{ number_format($tenant->currentLease->monthly_rent, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Lease History -->
                    @if($tenant->leaseContracts && $tenant->leaseContracts->count() > 0)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Lease History') }} ({{ $tenant->leaseContracts->count() }})</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Contract Code') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Start Date') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('End Date') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Monthly Rent') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($tenant->leaseContracts as $lease)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $lease->contract_code }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $lease->start_date->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $lease->end_date->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($lease->monthly_rent, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($lease->status === 'active') bg-green-100 text-green-800
                                                @elseif($lease->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($lease->status === 'expired') bg-gray-100 text-gray-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($lease->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Notes -->
                    @if($tenant->notes)
                    <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2">{{ __('Notes') }}</h3>
                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $tenant->notes }}</p>
                    </div>
                    @endif

                    <!-- Timestamps -->
                    <div class="mt-6 text-sm text-gray-500">
                        <p>{{ __('Created') }}: {{ $tenant->created_at->format('Y-m-d H:i') }}</p>
                        <p>{{ __('Last Updated') }}: {{ $tenant->updated_at->format('Y-m-d H:i') }}</p>
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
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">{{ __('Delete Tenant') }}</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">{{ __('Are you sure you want to delete this tenant? This action cannot be undone.') }}</p>
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
