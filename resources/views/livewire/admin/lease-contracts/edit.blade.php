<div>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">{{ __('Edit Lease Contract') }}</h2>
                        <a href="{{ route('admin.lease-contracts.show', $contract) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Back to Contract') }}
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

                    <form wire:submit="update">
                        <!-- Contract Parties -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Contract Parties') }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
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
                                <div>
                                    <label for="unit_id" class="block text-sm font-medium text-gray-700">{{ __('Unit') }} <span class="text-red-500">*</span></label>
                                    <select wire:model="unit_id" id="unit_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">{{ __('Select Unit') }}</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->unit_number }} ({{ number_format($unit->rent, 2) }})</option>
                                        @endforeach
                                    </select>
                                    @error('unit_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                    <label for="landlord_id" class="block text-sm font-medium text-gray-700">{{ __('Landlord') }} <span class="text-red-500">*</span></label>
                                    <select wire:model="landlord_id" id="landlord_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">{{ __('Select Landlord') }}</option>
                                        @foreach($landlords as $landlord)
                                            <option value="{{ $landlord->id }}">{{ $landlord->name }} ({{ $landlord->email }})</option>
                                        @endforeach
                                    </select>
                                    @error('landlord_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contract Dates -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Contract Dates') }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700">{{ __('Start Date') }} <span class="text-red-500">*</span></label>
                                    <input wire:model="start_date" type="date" id="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700">{{ __('End Date') }} <span class="text-red-500">*</span></label>
                                    <input wire:model="end_date" type="date" id="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="signed_date" class="block text-sm font-medium text-gray-700">{{ __('Signed Date') }}</label>
                                    <input wire:model="signed_date" type="date" id="signed_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('signed_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="move_in_date" class="block text-sm font-medium text-gray-700">{{ __('Move-in Date') }}</label>
                                    <input wire:model="move_in_date" type="date" id="move_in_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('move_in_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="move_out_date" class="block text-sm font-medium text-gray-700">{{ __('Move-out Date') }}</label>
                                    <input wire:model="move_out_date" type="date" id="move_out_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('move_out_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Financial Terms -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Financial Terms') }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label for="rent_amount" class="block text-sm font-medium text-gray-700">{{ __('Rent Amount') }} <span class="text-red-500">*</span></label>
                                    <input wire:model="rent_amount" type="number" step="0.01" id="rent_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('rent_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="security_deposit" class="block text-sm font-medium text-gray-700">{{ __('Security Deposit') }} <span class="text-red-500">*</span></label>
                                    <input wire:model="security_deposit" type="number" step="0.01" id="security_deposit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('security_deposit') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="broker_commission" class="block text-sm font-medium text-gray-700">{{ __('Broker Commission') }}</label>
                                    <input wire:model="broker_commission" type="number" step="0.01" id="broker_commission" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('broker_commission') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="rent_frequency" class="block text-sm font-medium text-gray-700">{{ __('Rent Frequency') }} <span class="text-red-500">*</span></label>
                                    <select wire:model="rent_frequency" id="rent_frequency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="monthly">{{ __('Monthly') }}</option>
                                        <option value="quarterly">{{ __('Quarterly') }}</option>
                                        <option value="semi_annual">{{ __('Semi-Annual') }}</option>
                                        <option value="annual">{{ __('Annual') }}</option>
                                    </select>
                                    @error('rent_frequency') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="payment_due_day" class="block text-sm font-medium text-gray-700">{{ __('Payment Due Day') }} <span class="text-red-500">*</span></label>
                                    <input wire:model="payment_due_day" type="number" min="1" max="31" id="payment_due_day" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('payment_due_day') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Late Fee Terms -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Late Fee Terms') }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="late_fee_amount" class="block text-sm font-medium text-gray-700">{{ __('Late Fee Amount') }}</label>
                                    <input wire:model="late_fee_amount" type="number" step="0.01" id="late_fee_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('late_fee_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="late_fee_grace_days" class="block text-sm font-medium text-gray-700">{{ __('Grace Days') }}</label>
                                    <input wire:model="late_fee_grace_days" type="number" min="0" max="30" id="late_fee_grace_days" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('late_fee_grace_days') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contract Terms -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Contract Terms') }}</h3>
                            
                            <div class="mb-4">
                                <label for="terms_conditions" class="block text-sm font-medium text-gray-700">{{ __('Terms & Conditions') }}</label>
                                <textarea wire:model="terms_conditions" id="terms_conditions" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                @error('terms_conditions') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="special_clauses" class="block text-sm font-medium text-gray-700">{{ __('Special Clauses') }}</label>
                                <textarea wire:model="special_clauses" id="special_clauses" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                @error('special_clauses') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Status & Termination -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Status') }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Contract Status') }} <span class="text-red-500">*</span></label>
                                    <select wire:model="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="draft">{{ __('Draft') }}</option>
                                        <option value="pending_signature">{{ __('Pending Signature') }}</option>
                                        <option value="active">{{ __('Active') }}</option>
                                        <option value="expired">{{ __('Expired') }}</option>
                                        <option value="terminated">{{ __('Terminated') }}</option>
                                        <option value="renewed">{{ __('Renewed') }}</option>
                                    </select>
                                    @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            @if($status === 'terminated')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-red-50 p-4 rounded-lg">
                                <div>
                                    <label for="termination_date" class="block text-sm font-medium text-gray-700">{{ __('Termination Date') }}</label>
                                    <input wire:model="termination_date" type="date" id="termination_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('termination_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="termination_reason" class="block text-sm font-medium text-gray-700">{{ __('Termination Reason') }}</label>
                                    <input wire:model="termination_reason" type="text" id="termination_reason" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('termination_reason') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                {{ __('Update Contract') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
