<div>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ __('Invoice') }}: {{ $invoice->invoice_number }}</h2>
                            @php
                                $statusColors = [
                                    'draft' => 'bg-gray-100 text-gray-800',
                                    'sent' => 'bg-blue-100 text-blue-800',
                                    'viewed' => 'bg-purple-100 text-purple-800',
                                    'partial_paid' => 'bg-yellow-100 text-yellow-800',
                                    'paid' => 'bg-green-100 text-green-800',
                                    'overdue' => 'bg-red-100 text-red-800',
                                    'cancelled' => 'bg-gray-100 text-gray-800',
                                    'refunded' => 'bg-orange-100 text-orange-800',
                                ];
                            @endphp
                            <span class="mt-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.invoices.edit', $invoice) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Edit') }}
                            </a>
                            <button wire:click="confirmDelete" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Delete') }}
                            </button>
                            <a href="{{ route('admin.invoices.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Back to List') }}
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

                    <!-- Invoice Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Contract Information -->
                        @if($invoice->contract)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-gray-700 mb-2">{{ __('Lease Contract') }}</h3>
                            <p class="text-sm text-gray-600">{{ $invoice->contract->contract_number }}</p>
                        </div>
                        @endif

                        <!-- Tenant Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-gray-700 mb-2">{{ __('Tenant') }}</h3>
                            <p class="text-sm text-gray-600">{{ $invoice->tenant->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $invoice->tenant->user->email }}</p>
                            @if($invoice->tenant->user->phone)
                                <p class="text-sm text-gray-500">{{ $invoice->tenant->user->phone }}</p>
                            @endif
                        </div>

                        <!-- Property Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-gray-700 mb-2">{{ __('Property') }}</h3>
                            <p class="text-sm text-gray-600">{{ $invoice->property->name }}</p>
                            @if($invoice->unit)
                                <p class="text-sm text-gray-500">{{ __('Unit') }}: {{ $invoice->unit->unit_number }}</p>
                            @endif
                        </div>

                        <!-- Invoice Type -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-gray-700 mb-2">{{ __('Invoice Type') }}</h3>
                            <p class="text-sm text-gray-600 capitalize">{{ str_replace('_', ' ', $invoice->type) }}</p>
                        </div>
                    </div>

                    <!-- Amounts Section -->
                    <div class="bg-blue-50 p-6 rounded-lg mb-6">
                        <h3 class="font-semibold text-gray-700 mb-4">{{ __('Amount Breakdown') }}</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('Subtotal') }}:</span>
                                <span class="font-medium">{{ number_format($invoice->subtotal, 2) }}</span>
                            </div>
                            @if($invoice->tax_amount > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('Tax') }}:</span>
                                <span class="font-medium">{{ number_format($invoice->tax_amount, 2) }}</span>
                            </div>
                            @endif
                            @if($invoice->discount_amount > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('Discount') }}:</span>
                                <span class="font-medium text-red-600">-{{ number_format($invoice->discount_amount, 2) }}</span>
                            </div>
                            @endif
                            <div class="border-t pt-2 flex justify-between">
                                <span class="text-lg font-semibold">{{ __('Total Amount') }}:</span>
                                <span class="text-lg font-bold text-blue-600">{{ number_format($invoice->total_amount, 2) }}</span>
                            </div>
                            @if($invoice->paid_amount > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('Paid Amount') }}:</span>
                                <span class="font-medium text-green-600">{{ number_format($invoice->paid_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('Remaining Balance') }}:</span>
                                <span class="font-medium text-orange-600">{{ number_format($invoice->remaining_balance, 2) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Dates Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-gray-700 mb-2">{{ __('Invoice Date') }}</h3>
                            <p class="text-sm text-gray-600">{{ $invoice->invoice_date->format('Y-m-d') }}</p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-gray-700 mb-2">{{ __('Due Date') }}</h3>
                            <p class="text-sm text-gray-600">{{ $invoice->due_date->format('Y-m-d') }}</p>
                            @if($invoice->isOverdue())
                                <p class="text-xs text-red-600 mt-1">{{ __('Overdue') }}</p>
                            @endif
                        </div>

                        @if($invoice->service_period_start && $invoice->service_period_end)
                        <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
                            <h3 class="font-semibold text-gray-700 mb-2">{{ __('Service Period') }}</h3>
                            <p class="text-sm text-gray-600">
                                {{ $invoice->service_period_start->format('Y-m-d') }} {{ __('to') }} {{ $invoice->service_period_end->format('Y-m-d') }}
                            </p>
                        </div>
                        @endif
                    </div>

                    <!-- Description -->
                    @if($invoice->description)
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="font-semibold text-gray-700 mb-2">{{ __('Description') }}</h3>
                        <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $invoice->description }}</p>
                    </div>
                    @endif

                    <!-- Payment Terms -->
                    @if($invoice->payment_terms)
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="font-semibold text-gray-700 mb-2">{{ __('Payment Terms') }}</h3>
                        <p class="text-sm text-gray-600">{{ $invoice->payment_terms }}</p>
                    </div>
                    @endif

                    <!-- Notes -->
                    @if($invoice->notes)
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="font-semibold text-gray-700 mb-2">{{ __('Notes') }}</h3>
                        <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $invoice->notes }}</p>
                    </div>
                    @endif

                    <!-- Payments Section -->
                    @if($invoice->payments->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Payments') }}</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Date') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Amount') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Method') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Reference') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($invoice->payments as $payment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment->payment_date->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($payment->amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ $payment->payment_method }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->reference_number ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Metadata -->
                    <div class="border-t pt-4 mt-6 text-sm text-gray-500">
                        <p>{{ __('Created') }}: {{ $invoice->created_at->format('Y-m-d H:i') }}
                            @if($invoice->creator)
                                {{ __('by') }} {{ $invoice->creator->name }}
                            @endif
                        </p>
                        <p>{{ __('Last Updated') }}: {{ $invoice->updated_at->format('Y-m-d H:i') }}</p>
                        @if($invoice->sent_at)
                            <p>{{ __('Sent') }}: {{ $invoice->sent_at->format('Y-m-d H:i') }}</p>
                        @endif
                        @if($invoice->viewed_at)
                            <p>{{ __('Viewed') }}: {{ $invoice->viewed_at->format('Y-m-d H:i') }}</p>
                        @endif
                        @if($invoice->paid_at)
                            <p>{{ __('Paid') }}: {{ $invoice->paid_at->format('Y-m-d H:i') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($deleteId)
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
                            <div class="mt-3 text-center sm:mt-0 {{ app()->getLocale() === 'ar' ? 'sm:mr-4' : 'sm:ml-4' }} sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    {{ __('Delete Invoice') }}
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        {{ __('Are you sure you want to delete this invoice? This action cannot be undone. Invoices with payments cannot be deleted.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="delete" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} sm:w-auto sm:text-sm">
                            {{ __('Delete') }}
                        </button>
                        <button wire:click="$set('deleteId', null)" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:{{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }} sm:w-auto sm:text-sm">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
