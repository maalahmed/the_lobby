<div>
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">{{ __('Payment Details') }}: {{ $payment->payment_reference }}</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.payments.edit', $payment) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Edit') }}
            </a>
            @if($payment->status !== 'completed')
            <button wire:click="$dispatch('openModal', { component: 'admin.payments.delete-modal', arguments: { payment: {{ $payment->id }} } })" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Delete') }}
            </button>
            @endif
            <a href="{{ route('admin.payments.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Back') }}
            </a>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Payment Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    {{ __('Payment Information') }}
                </h2>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Amount') }}</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format((float)$payment->amount, 2) }} {{ $payment->currency }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Payment Method') }}</p>
                        <p class="text-lg font-semibold">{{ __(ucwords(str_replace('_', ' ', $payment->payment_method))) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Payment Date') }}</p>
                        <p class="font-medium">{{ $payment->payment_date->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Status') }}</p>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            @if($payment->status === 'completed') bg-green-100 text-green-800
                            @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($payment->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($payment->status === 'failed') bg-red-100 text-red-800
                            @elseif($payment->status === 'cancelled') bg-gray-100 text-gray-800
                            @elseif($payment->status === 'refunded') bg-orange-100 text-orange-800
                            @endif">
                            {{ __(ucfirst($payment->status)) }}
                        </span>
                    </div>
                </div>

                @if($payment->isRefunded())
                <div class="mt-4 p-4 bg-orange-50 border border-orange-200 rounded">
                    <p class="text-sm font-semibold text-orange-800">{{ __('Refund Information') }}</p>
                    <p class="text-sm text-orange-700">{{ __('Refunded Amount') }}: {{ number_format((float)$payment->refunded_amount, 2) }} {{ $payment->currency }}</p>
                    @if($payment->refund_reason)
                    <p class="text-sm text-orange-700 mt-1">{{ __('Reason') }}: {{ $payment->refund_reason }}</p>
                    @endif
                    <p class="text-xs text-orange-600 mt-1">{{ __('Refunded on') }}: {{ $payment->refunded_at->format('M d, Y') }}</p>
                </div>
                @endif
            </div>

            <!-- Related Entities -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('Related Information') }}</h2>
                
                <div class="space-y-4">
                    @if($payment->invoice)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Invoice') }}</p>
                            <p class="font-medium">{{ $payment->invoice->invoice_number }}</p>
                        </div>
                        <a href="{{ route('admin.invoices.show', $payment->invoice) }}" class="text-indigo-600 hover:text-indigo-900">
                            {{ __('View Invoice') }}
                        </a>
                    </div>
                    @endif

                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Tenant') }}</p>
                            <p class="font-medium">{{ $payment->tenant->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $payment->tenant->user->email }}</p>
                        </div>
                        <a href="{{ route('admin.tenants.show', $payment->tenant) }}" class="text-indigo-600 hover:text-indigo-900">
                            {{ __('View Tenant') }}
                        </a>
                    </div>

                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Property') }}</p>
                            <p class="font-medium">{{ $payment->property->name }}</p>
                        </div>
                        <a href="{{ route('admin.properties.show', $payment->property) }}" class="text-indigo-600 hover:text-indigo-900">
                            {{ __('View Property') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Gateway/Bank Details -->
            @if($payment->gateway_name || $payment->bank_name)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('Transaction Details') }}</h2>
                
                <div class="grid grid-cols-2 gap-4">
                    @if($payment->gateway_name)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Gateway') }}</p>
                        <p class="font-medium">{{ $payment->gateway_name }}</p>
                    </div>
                    @endif
                    
                    @if($payment->gateway_transaction_id)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Transaction ID') }}</p>
                        <p class="font-medium font-mono text-sm">{{ $payment->gateway_transaction_id }}</p>
                    </div>
                    @endif

                    @if($payment->bank_name)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Bank Name') }}</p>
                        <p class="font-medium">{{ $payment->bank_name }}</p>
                    </div>
                    @endif

                    @if($payment->check_number)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Check Number') }}</p>
                        <p class="font-medium">{{ $payment->check_number }}</p>
                    </div>
                    @endif

                    @if($payment->bank_reference)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Bank Reference') }}</p>
                        <p class="font-medium">{{ $payment->bank_reference }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Notes -->
            @if($payment->notes)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('Notes') }}</h2>
                <p class="text-gray-700">{{ $payment->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Verification Status -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('Verification') }}</h2>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Status') }}</p>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            @if($payment->verification_status === 'verified') bg-green-100 text-green-800
                            @elseif($payment->verification_status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($payment->verification_status === 'rejected') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ __(ucfirst(str_replace('_', ' ', $payment->verification_status))) }}
                        </span>
                    </div>

                    @if($payment->verified_at)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Verified At') }}</p>
                        <p class="text-sm">{{ $payment->verified_at->format('M d, Y H:i') }}</p>
                    </div>
                    @endif

                    @if($payment->verifier)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Verified By') }}</p>
                        <p class="text-sm font-medium">{{ $payment->verifier->name }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Receipt -->
            @if($payment->receipt_url)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('Receipt') }}</h2>
                <a href="{{ $payment->receipt_url }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('Download Receipt') }}
                </a>
            </div>
            @endif

            <!-- Metadata -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('Metadata') }}</h2>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Created') }}</p>
                        <p class="text-sm">{{ $payment->created_at->format('M d, Y H:i') }}</p>
                        @if($payment->creator)
                        <p class="text-xs text-gray-500">{{ __('by') }} {{ $payment->creator->name }}</p>
                        @endif
                    </div>

                    @if($payment->processed_at)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Processed At') }}</p>
                        <p class="text-sm">{{ $payment->processed_at->format('M d, Y H:i') }}</p>
                    </div>
                    @endif

                    <div>
                        <p class="text-sm text-gray-600">{{ __('Last Updated') }}</p>
                        <p class="text-sm">{{ $payment->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
