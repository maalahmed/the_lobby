<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">{{ __('Create Maintenance Job') }}</h1>
        <p class="mt-1 text-sm text-gray-600">{{ __('Assign a service provider to a maintenance request') }}</p>
    </div>

    <form wire:submit="save">
        <div class="bg-white rounded-lg shadow p-6 space-y-6">
            
            <!-- Maintenance Request -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Maintenance Request') }}</h3>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="maintenance_request_id" class="block text-sm font-medium text-gray-700">{{ __('Request') }} *</label>
                        <select wire:model="maintenance_request_id" id="maintenance_request_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('Select Request') }}</option>
                            @foreach($maintenanceRequests as $request)
                                <option value="{{ $request->id }}">
                                    {{ $request->request_number }} - {{ $request->title }} 
                                    ({{ $request->property->name }}@if($request->unit) - Unit {{ $request->unit->unit_number }}@endif)
                                </option>
                            @endforeach
                        </select>
                        @error('maintenance_request_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Service Provider -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Service Provider') }}</h3>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="service_provider_id" class="block text-sm font-medium text-gray-700">{{ __('Provider') }} *</label>
                        <select wire:model="service_provider_id" id="service_provider_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('Select Service Provider') }}</option>
                            @foreach($serviceProviders as $provider)
                                <option value="{{ $provider->id }}">{{ $provider->company_name }}</option>
                            @endforeach
                        </select>
                        @error('service_provider_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Work Details -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Work Details') }}</h3>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="work_description" class="block text-sm font-medium text-gray-700">{{ __('Work Description') }} *</label>
                        <textarea wire:model="work_description" id="work_description" rows="4"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="{{ __('Describe the work to be done...') }}"></textarea>
                        @error('work_description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Schedule -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Schedule') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="scheduled_date" class="block text-sm font-medium text-gray-700">{{ __('Scheduled Date') }} *</label>
                        <input type="date" wire:model="scheduled_date" id="scheduled_date"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('scheduled_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="scheduled_time_start" class="block text-sm font-medium text-gray-700">{{ __('Start Time') }}</label>
                        <input type="time" wire:model="scheduled_time_start" id="scheduled_time_start"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('scheduled_time_start') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="scheduled_time_end" class="block text-sm font-medium text-gray-700">{{ __('End Time') }}</label>
                        <input type="time" wire:model="scheduled_time_end" id="scheduled_time_end"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('scheduled_time_end') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Financial -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Financial Details') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="quoted_amount" class="block text-sm font-medium text-gray-700">{{ __('Quoted Amount (SAR)') }}</label>
                        <input type="number" step="0.01" wire:model="quoted_amount" id="quoted_amount"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('quoted_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="payment_due_date" class="block text-sm font-medium text-gray-700">{{ __('Payment Due Date') }}</label>
                        <input type="date" wire:model="payment_due_date" id="payment_due_date"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('payment_due_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Status') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Job Status') }} *</label>
                        <select wire:model="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="assigned">{{ __('Assigned') }}</option>
                            <option value="accepted">{{ __('Accepted') }}</option>
                            <option value="rejected">{{ __('Rejected') }}</option>
                            <option value="in_progress">{{ __('In Progress') }}</option>
                            <option value="completed">{{ __('Completed') }}</option>
                            <option value="cancelled">{{ __('Cancelled') }}</option>
                        </select>
                        @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700">{{ __('Payment Status') }} *</label>
                        <select wire:model="payment_status" id="payment_status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="pending">{{ __('Pending') }}</option>
                            <option value="approved">{{ __('Approved') }}</option>
                            <option value="paid">{{ __('Paid') }}</option>
                        </select>
                        @error('payment_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Additional Notes') }}</h3>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">{{ __('Notes') }}</label>
                        <textarea wire:model="notes" id="notes" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('admin.maintenance-jobs.index') }}" 
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    {{ __('Create Job') }}
                </button>
            </div>
        </div>
    </form>
    </div>
        </div>
</div>
