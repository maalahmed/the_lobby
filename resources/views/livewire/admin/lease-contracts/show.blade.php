<div>
    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ __('Lease Contract') }}: {{ $contract->contract_number }}</h2>
                            <p class="text-sm text-gray-600 mt-1">
                                @if($contract->status === 'active')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">{{ __('Active') }}</span>
                                @elseif($contract->status === 'draft')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ __('Draft') }}</span>
                                @elseif($contract->status === 'pending_signature')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ __('Pending Signature') }}</span>
                                @elseif($contract->status === 'expired')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">{{ __('Expired') }}</span>
                                @elseif($contract->status === 'terminated')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">{{ __('Terminated') }}</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ ucfirst($contract->status) }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.lease-contracts.edit', $contract) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Edit') }}
                            </a>
                            <button wire:click="$set('showDeleteModal', true)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Delete') }}
                            </button>
                            <a href="{{ route('admin.lease-contracts.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
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
                        <!-- Property & Unit Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Property & Unit') }}</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Property') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $contract->property->name }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Unit Number') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $contract->unit->unit_number }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Address') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $contract->property->address }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tenant Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Tenant Information') }}</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Name') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $contract->tenant->user->name }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Email') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $contract->tenant->user->email }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Phone') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $contract->tenant->user->phone ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Landlord Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Landlord Information') }}</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Name') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $contract->landlord->name }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Email') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $contract->landlord->email }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Contract Dates -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Contract Dates') }}</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Start Date') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $contract->start_date->format('Y-m-d') }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('End Date') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $contract->end_date->format('Y-m-d') }}</span>
                                </div>
                                @if($contract->signed_date)
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Signed Date') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $contract->signed_date->format('Y-m-d') }}</span>
                                </div>
                                @endif
                                @if($contract->move_in_date)
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Move-in Date') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $contract->move_in_date->format('Y-m-d') }}</span>
                                </div>
                                @endif
                                @if($contract->move_out_date)
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Move-out Date') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $contract->move_out_date->format('Y-m-d') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Financial Terms -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Financial Terms') }}</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Rent Amount') }}:</span>
                                    <span class="text-sm text-gray-900">{{ number_format($contract->rent_amount, 2) }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Frequency') }}:</span>
                                    <span class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $contract->rent_frequency)) }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Security Deposit') }}:</span>
                                    <span class="text-sm text-gray-900">{{ number_format($contract->security_deposit, 2) }}</span>
                                </div>
                                @if($contract->broker_commission > 0)
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Broker Commission') }}:</span>
                                    <span class="text-sm text-gray-900">{{ number_format($contract->broker_commission, 2) }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Payment Terms -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Payment Terms') }}</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Payment Due Day') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $contract->payment_due_day }} {{ __('of each month') }}</span>
                                </div>
                                @if($contract->late_fee_amount > 0)
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Late Fee') }}:</span>
                                    <span class="text-sm text-gray-900">{{ number_format($contract->late_fee_amount, 2) }}</span>
                                </div>
                                @endif
                                <div>
                                    <span class="text-sm font-medium text-gray-600">{{ __('Grace Days') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $contract->late_fee_grace_days }} {{ __('days') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    @if($contract->terms_conditions)
                    <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2">{{ __('Terms & Conditions') }}</h3>
                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $contract->terms_conditions }}</p>
                    </div>
                    @endif

                    <!-- Special Clauses -->
                    @if($contract->special_clauses)
                    <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2">{{ __('Special Clauses') }}</h3>
                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $contract->special_clauses }}</p>
                    </div>
                    @endif

                    <!-- Termination Information -->
                    @if($contract->status === 'terminated' && $contract->termination_reason)
                    <div class="mt-6 bg-red-50 p-4 rounded-lg border border-red-200">
                        <h3 class="text-lg font-semibold mb-2 text-red-800">{{ __('Termination Information') }}</h3>
                        <div class="space-y-2">
                            @if($contract->termination_date)
                            <div>
                                <span class="text-sm font-medium text-gray-600">{{ __('Termination Date') }}:</span>
                                <span class="text-sm text-gray-900">{{ $contract->termination_date->format('Y-m-d') }}</span>
                            </div>
                            @endif
                            <div>
                                <span class="text-sm font-medium text-gray-600">{{ __('Reason') }}:</span>
                                <span class="text-sm text-gray-900">{{ $contract->termination_reason }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Invoices -->
                    @if($contract->invoices && $contract->invoices->count() > 0)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Associated Invoices') }} ({{ $contract->invoices->count() }})</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Invoice #') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Due Date') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Amount') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($contract->invoices as $invoice)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $invoice->invoice_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $invoice->due_date->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($invoice->total_amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                @if($invoice->status === 'paid') bg-green-100 text-green-800
                                                @elseif($invoice->status === 'overdue') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Metadata -->
                    <div class="mt-6 text-sm text-gray-500 border-t pt-4">
                        <p>{{ __('Created') }}: {{ $contract->created_at->format('Y-m-d H:i') }}
                            @if($contract->creator)
                                {{ __('by') }} {{ $contract->creator->name }}
                            @endif
                        </p>
                        <p>{{ __('Last Updated') }}: {{ $contract->updated_at->format('Y-m-d H:i') }}</p>
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
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">{{ __('Delete Lease Contract') }}</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">{{ __('Are you sure you want to delete this lease contract? This action cannot be undone.') }}</p>
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
