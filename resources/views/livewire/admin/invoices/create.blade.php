<div>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">{{ __('Create Invoice') }}</h2>
                        <a href="{{ route('admin.invoices.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Back to List') }}
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

                    <form wire:submit="save">
                        <!-- Contract & Parties -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Contract & Parties') }}</h3>
                            
                            <div class="mb-4">
                                <label for="contract_id" class="block text-sm font-medium text-gray-700">{{ __('Lease Contract') }} <span class="text-xs text-gray-500">({{ __('Optional') }})</span></label>
                                <select wire:model.live="contract_id" id="contract_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">{{ __('Select Contract (optional)') }}</option>
                                    @foreach($contracts as $contract)
                                        <option value="{{ $contract->id }}">
                                            {{ $contract->contract_number }} - {{ $contract->tenant->user->name }} ({{ $contract->property->name }} - {{ $contract->unit->unit_number }})
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500">{{ __('Select a contract to auto-populate tenant, property, and unit') }}</p>
                                @error('contract_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="tenant_id" class="block text-sm font-medium text-gray-700">{{ __('Tenant') }} <span class="text-red-500">*</span></label>
                                    <select wire:model="tenant_id" id="tenant_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">{{ __('Select Tenant') }}</option>
                                        @foreach($tenants as $tenant)
                                            <option value="{{ $tenant->id }}">{{ $tenant->user->name }} ({{ $tenant->user->email }})</option>
                                        @endforeach
                                    </select>
                                    @error('tenant_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="property_id" class="block text-sm font-medium text-gray-700">{{ __('Property') }} <span class="text-red-500">*</span></label>
                                    <select wire:model.live="property_id" id="property_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">{{ __('Select Property') }}</option>
                                        @foreach($properties as $property)
                                            <option value="{{ $property->id }}">{{ $property->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('property_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="unit_id" class="block text-sm font-medium text-gray-700">{{ __('Unit') }} <span class="text-xs text-gray-500">({{ __('Optional') }})</span></label>
                                <select wire:model="unit_id" id="unit_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ empty($property_id) ? 'disabled' : '' }}>
                                    <option value="">{{ __('Select Unit (optional)') }}</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->unit_number }}</option>
                                    @endforeach
                                </select>
                                @error('unit_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Invoice Details -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Invoice Details') }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700">{{ __('Type') }} <span class="text-red-500">*</span></label>
                                    <select wire:model="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="rent">{{ __('Rent') }}</option>
                                        <option value="deposit">{{ __('Deposit') }}</option>
                                        <option value="late_fee">{{ __('Late Fee') }}</option>
                                        <option value="utility">{{ __('Utility') }}</option>
                                        <option value="maintenance">{{ __('Maintenance') }}</option>
                                        <option value="service">{{ __('Service') }}</option>
                                        <option value="other">{{ __('Other') }}</option>
                                    </select>
                                    @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Status') }} <span class="text-red-500">*</span></label>
                                    <select wire:model="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="draft">{{ __('Draft') }}</option>
                                        <option value="sent">{{ __('Sent') }}</option>
                                        <option value="viewed">{{ __('Viewed') }}</option>
                                        <option value="partial_paid">{{ __('Partially Paid') }}</option>
                                        <option value="paid">{{ __('Paid') }}</option>
                                        <option value="overdue">{{ __('Overdue') }}</option>
                                        <option value="cancelled">{{ __('Cancelled') }}</option>
                                    </select>
                                    @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                                <textarea wire:model="description" id="description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Amounts -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Amounts') }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label for="subtotal" class="block text-sm font-medium text-gray-700">{{ __('Subtotal') }} <span class="text-red-500">*</span></label>
                                    <input wire:model.live="subtotal" type="number" step="0.01" id="subtotal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('subtotal') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="tax_amount" class="block text-sm font-medium text-gray-700">{{ __('Tax Amount') }}</label>
                                    <input wire:model.live="tax_amount" type="number" step="0.01" id="tax_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('tax_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="discount_amount" class="block text-sm font-medium text-gray-700">{{ __('Discount Amount') }}</label>
                                    <input wire:model.live="discount_amount" type="number" step="0.01" id="discount_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('discount_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mb-4 bg-gray-50 p-4 rounded-lg">
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Total Amount') }}</label>
                                <div class="text-2xl font-bold text-gray-900">{{ number_format($total_amount, 2) }}</div>
                                <p class="text-xs text-gray-500 mt-1">{{ __('Calculated: Subtotal + Tax - Discount') }}</p>
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Dates') }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="invoice_date" class="block text-sm font-medium text-gray-700">{{ __('Invoice Date') }} <span class="text-red-500">*</span></label>
                                    <input wire:model="invoice_date" type="date" id="invoice_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('invoice_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="due_date" class="block text-sm font-medium text-gray-700">{{ __('Due Date') }} <span class="text-red-500">*</span></label>
                                    <input wire:model="due_date" type="date" id="due_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('due_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="service_period_start" class="block text-sm font-medium text-gray-700">{{ __('Service Period Start') }}</label>
                                    <input wire:model="service_period_start" type="date" id="service_period_start" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('service_period_start') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="service_period_end" class="block text-sm font-medium text-gray-700">{{ __('Service Period End') }}</label>
                                    <input wire:model="service_period_end" type="date" id="service_period_end" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('service_period_end') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Additional Information') }}</h3>
                            
                            <div class="mb-4">
                                <label for="payment_terms" class="block text-sm font-medium text-gray-700">{{ __('Payment Terms') }}</label>
                                <input wire:model="payment_terms" type="text" id="payment_terms" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('payment_terms') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="block text-sm font-medium text-gray-700">{{ __('Notes') }}</label>
                                <textarea wire:model="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                {{ __('Create Invoice') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
