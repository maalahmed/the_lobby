<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">{{ __('Edit Payment') }}: {{ $payment->payment_reference }}</h1>
        <a href="{{ route('admin.payments.show', $payment) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            {{ __('Back to Payment') }}
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form wire:submit.prevent="update">
            
            <!-- Invoice & Parties Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('Invoice & Parties') }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="invoice_id" class="block text-sm font-medium text-gray-700">{{ __('Invoice') }}</label>
                        <select wire:model.live="invoice_id" id="invoice_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">{{ __('Select Invoice (Optional)') }}</option>
                            @foreach($invoices as $invoice)
                                <option value="{{ $invoice->id }}">
                                    {{ $invoice->invoice_number }} - {{ $invoice->tenant->user->name }} - {{ number_format((float)($invoice->total_amount - $invoice->paid_amount), 2) }} {{ $invoice->currency }}
                                </option>
                            @endforeach
                        </select>
                        @error('invoice_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="tenant_id" class="block text-sm font-medium text-gray-700">{{ __('Tenant') }} <span class="text-red-500">*</span></label>
                        <select wire:model="tenant_id" id="tenant_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            <option value="">{{ __('Select Tenant') }}</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}">{{ $tenant->user->name }} - {{ $tenant->user->email }}</option>
                            @endforeach
                        </select>
                        @error('tenant_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="property_id" class="block text-sm font-medium text-gray-700">{{ __('Property') }} <span class="text-red-500">*</span></label>
                    <select wire:model="property_id" id="property_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="">{{ __('Select Property') }}</option>
                        @foreach($properties as $property)
                            <option value="{{ $property->id }}">{{ $property->name }}</option>
                        @endforeach
                    </select>
                    @error('property_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Payment Details Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('Payment Details') }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">{{ __('Amount') }} <span class="text-red-500">*</span></label>
                        <input wire:model="amount" type="number" step="0.01" id="amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700">{{ __('Currency') }} <span class="text-red-500">*</span></label>
                        <select wire:model="currency" id="currency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            <option value="SAR">SAR</option>
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                        </select>
                        @error('currency') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700">{{ __('Payment Date') }} <span class="text-red-500">*</span></label>
                        <input wire:model="payment_date" type="date" id="payment_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        @error('payment_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">{{ __('Payment Method') }} <span class="text-red-500">*</span></label>
                        <select wire:model="payment_method" id="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            <option value="cash">{{ __('Cash') }}</option>
                            <option value="bank_transfer">{{ __('Bank Transfer') }}</option>
                            <option value="check">{{ __('Check') }}</option>
                            <option value="card">{{ __('Card') }}</option>
                            <option value="online">{{ __('Online') }}</option>
                            <option value="mobile_payment">{{ __('Mobile Payment') }}</option>
                        </select>
                        @error('payment_method') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Status') }} <span class="text-red-500">*</span></label>
                        <select wire:model="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            <option value="pending">{{ __('Pending') }}</option>
                            <option value="processing">{{ __('Processing') }}</option>
                            <option value="completed">{{ __('Completed') }}</option>
                            <option value="failed">{{ __('Failed') }}</option>
                            <option value="cancelled">{{ __('Cancelled') }}</option>
                            <option value="refunded">{{ __('Refunded') }}</option>
                        </select>
                        @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="verification_status" class="block text-sm font-medium text-gray-700">{{ __('Verification Status') }} <span class="text-red-500">*</span></label>
                    <select wire:model="verification_status" id="verification_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="not_required">{{ __('Not Required') }}</option>
                        <option value="pending">{{ __('Pending Verification') }}</option>
                        <option value="verified">{{ __('Verified') }}</option>
                        <option value="rejected">{{ __('Rejected') }}</option>
                    </select>
                    @error('verification_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Refund Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('Refund Information') }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="refunded_amount" class="block text-sm font-medium text-gray-700">{{ __('Refunded Amount') }}</label>
                        <input wire:model="refunded_amount" type="number" step="0.01" min="0" max="{{ $amount }}" id="refunded_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <p class="text-xs text-gray-500 mt-1">{{ __('Maximum refundable amount') }}: {{ number_format((float)$amount, 2) }}</p>
                        @error('refunded_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="refund_reason" class="block text-sm font-medium text-gray-700">{{ __('Refund Reason') }}</label>
                        <textarea wire:model="refund_reason" id="refund_reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                        @error('refund_reason') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Gateway Details (Conditional) -->
            @if(in_array($payment_method, ['online', 'card', 'mobile_payment']))
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('Gateway Details') }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="gateway_name" class="block text-sm font-medium text-gray-700">{{ __('Gateway Name') }}</label>
                        <input wire:model="gateway_name" type="text" id="gateway_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('gateway_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="gateway_transaction_id" class="block text-sm font-medium text-gray-700">{{ __('Transaction ID') }}</label>
                        <input wire:model="gateway_transaction_id" type="text" id="gateway_transaction_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('gateway_transaction_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            @endif

            <!-- Bank/Check Details (Conditional) -->
            @if(in_array($payment_method, ['bank_transfer', 'check']))
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('Bank/Check Details') }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="bank_name" class="block text-sm font-medium text-gray-700">{{ __('Bank Name') }}</label>
                        <input wire:model="bank_name" type="text" id="bank_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('bank_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    @if($payment_method === 'check')
                    <div>
                        <label for="check_number" class="block text-sm font-medium text-gray-700">{{ __('Check Number') }}</label>
                        <input wire:model="check_number" type="text" id="check_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('check_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <div>
                        <label for="bank_reference" class="block text-sm font-medium text-gray-700">{{ __('Bank Reference') }}</label>
                        <input wire:model="bank_reference" type="text" id="bank_reference" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('bank_reference') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            @endif

            <!-- Additional Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('Additional Information') }}</h3>
                
                <div class="mb-4">
                    <label for="receipt_url" class="block text-sm font-medium text-gray-700">{{ __('Receipt URL') }}</label>
                    <input wire:model="receipt_url" type="url" id="receipt_url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('receipt_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">{{ __('Notes') }}</label>
                    <textarea wire:model="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                    @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded">
                    {{ __('Update Payment') }}
                </button>
            </div>
        </form>
    </div>
</div>
