<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">{{ __('Invoices') }}</h2>
                        <a href="{{ route('admin.invoices.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Create New Invoice') }}
                        </a>
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

                    <!-- Filters -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <input wire:model.live="search" type="text" placeholder="{{ __('Search invoices...') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <select wire:model.live="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ __('All Statuses') }}</option>
                                <option value="draft">{{ __('Draft') }}</option>
                                <option value="sent">{{ __('Sent') }}</option>
                                <option value="viewed">{{ __('Viewed') }}</option>
                                <option value="partial_paid">{{ __('Partially Paid') }}</option>
                                <option value="paid">{{ __('Paid') }}</option>
                                <option value="overdue">{{ __('Overdue') }}</option>
                                <option value="cancelled">{{ __('Cancelled') }}</option>
                                <option value="refunded">{{ __('Refunded') }}</option>
                            </select>
                        </div>
                        <div>
                            <select wire:model.live="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ __('All Types') }}</option>
                                <option value="rent">{{ __('Rent') }}</option>
                                <option value="deposit">{{ __('Deposit') }}</option>
                                <option value="late_fee">{{ __('Late Fee') }}</option>
                                <option value="utility">{{ __('Utility') }}</option>
                                <option value="maintenance">{{ __('Maintenance') }}</option>
                                <option value="service">{{ __('Service') }}</option>
                                <option value="other">{{ __('Other') }}</option>
                            </select>
                        </div>
                        <div>
                            <select wire:model.live="property_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ __('All Properties') }}</option>
                                @foreach($properties as $property)
                                    <option value="{{ $property->id }}">{{ $property->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Invoices Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Invoice #') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Tenant') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Property/Unit') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Type') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Amount') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Due Date') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($invoices as $invoice)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $invoice->invoice_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $invoice->tenant->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>{{ $invoice->property->name }}</div>
                                            @if($invoice->unit)
                                                <div class="text-xs text-gray-400">{{ $invoice->unit->unit_number }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="capitalize">{{ str_replace('_', ' ', $invoice->type) }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div>{{ number_format($invoice->total_amount, 2) }}</div>
                                            @if($invoice->paid_amount > 0)
                                                <div class="text-xs text-green-600">Paid: {{ number_format($invoice->paid_amount, 2) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $invoice->due_date->format('Y-m-d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
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
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.invoices.show', $invoice) }}" class="text-indigo-600 hover:text-indigo-900 {{ app()->getLocale() === 'ar' ? 'ml-4' : 'mr-4' }}">{{ __('View') }}</a>
                                            <a href="{{ route('admin.invoices.edit', $invoice) }}" class="text-green-600 hover:text-green-900 {{ app()->getLocale() === 'ar' ? 'ml-4' : 'mr-4' }}">{{ __('Edit') }}</a>
                                            <button wire:click="confirmDelete({{ $invoice->id }})" class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                            {{ __('No invoices found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $invoices->links() }}
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
