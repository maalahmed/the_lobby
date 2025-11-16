<div>
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">{{ __('Create Maintenance Request') }}</h1>
        <a href="{{ route('admin.maintenance-requests.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            {{ __('Back to Requests') }}
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form wire:submit.prevent="save">
            
            <!-- Location Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('Location') }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="property_id" class="block text-sm font-medium text-gray-700">{{ __('Property') }} <span class="text-red-500">*</span></label>
                        <select wire:model.live="property_id" id="property_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            <option value="">{{ __('Select Property') }}</option>
                            @foreach($properties as $property)
                                <option value="{{ $property->id }}">{{ $property->name }}</option>
                            @endforeach
                        </select>
                        @error('property_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="unit_id" class="block text-sm font-medium text-gray-700">{{ __('Unit') }}</label>
                        <select wire:model="unit_id" id="unit_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">{{ __('Select Unit (Optional)') }}</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->unit_number }} - {{ $unit->type }}</option>
                            @endforeach
                        </select>
                        @error('unit_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="tenant_id" class="block text-sm font-medium text-gray-700">{{ __('Tenant') }}</label>
                    <select wire:model="tenant_id" id="tenant_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">{{ __('Select Tenant (Optional)') }}</option>
                        @foreach($tenants as $tenant)
                            <option value="{{ $tenant->id }}">{{ $tenant->user->name }} - {{ $tenant->user->email }}</option>
                        @endforeach
                    </select>
                    @error('tenant_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Request Details Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('Request Details') }}</h3>
                
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">{{ __('Title') }} <span class="text-red-500">*</span></label>
                    <input wire:model="title" type="text" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Description') }} <span class="text-red-500">*</span></label>
                    <textarea wire:model="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">{{ __('Category') }} <span class="text-red-500">*</span></label>
                        <select wire:model="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            <option value="plumbing">{{ __('Plumbing') }}</option>
                            <option value="electrical">{{ __('Electrical') }}</option>
                            <option value="hvac">{{ __('HVAC') }}</option>
                            <option value="appliance">{{ __('Appliance') }}</option>
                            <option value="structural">{{ __('Structural') }}</option>
                            <option value="pest_control">{{ __('Pest Control') }}</option>
                            <option value="cleaning">{{ __('Cleaning') }}</option>
                            <option value="landscaping">{{ __('Landscaping') }}</option>
                            <option value="security">{{ __('Security') }}</option>
                            <option value="other">{{ __('Other') }}</option>
                        </select>
                        @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700">{{ __('Priority') }} <span class="text-red-500">*</span></label>
                        <select wire:model="priority" id="priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            <option value="low">{{ __('Low') }}</option>
                            <option value="normal">{{ __('Normal') }}</option>
                            <option value="high">{{ __('High') }}</option>
                            <option value="urgent">{{ __('Urgent') }}</option>
                        </select>
                        @error('priority') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Status') }} <span class="text-red-500">*</span></label>
                        <select wire:model="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            <option value="pending">{{ __('Pending') }}</option>
                            <option value="approved">{{ __('Approved') }}</option>
                            <option value="assigned">{{ __('Assigned') }}</option>
                            <option value="in_progress">{{ __('In Progress') }}</option>
                            <option value="on_hold">{{ __('On Hold') }}</option>
                            <option value="completed">{{ __('Completed') }}</option>
                            <option value="cancelled">{{ __('Cancelled') }}</option>
                        </select>
                        @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Assignment Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('Assignment') }}</h3>
                
                <div>
                    <label for="assigned_to" class="block text-sm font-medium text-gray-700">{{ __('Assign To') }}</label>
                    <select wire:model="assigned_to" id="assigned_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">{{ __('Select User (Optional)') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('assigned_to') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Scheduling Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('Scheduling') }}</h3>
                
                <div class="mb-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">{{ __('Preferred Time') }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="preferred_date" class="block text-xs text-gray-600">{{ __('Date') }}</label>
                            <input wire:model="preferred_date" type="date" id="preferred_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('preferred_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="preferred_time_start" class="block text-xs text-gray-600">{{ __('Start Time') }}</label>
                            <input wire:model="preferred_time_start" type="time" id="preferred_time_start" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('preferred_time_start') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="preferred_time_end" class="block text-xs text-gray-600">{{ __('End Time') }}</label>
                            <input wire:model="preferred_time_end" type="time" id="preferred_time_end" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('preferred_time_end') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">{{ __('Scheduled Time') }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="scheduled_date" class="block text-xs text-gray-600">{{ __('Date') }}</label>
                            <input wire:model="scheduled_date" type="date" id="scheduled_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('scheduled_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="scheduled_time_start" class="block text-xs text-gray-600">{{ __('Start Time') }}</label>
                            <input wire:model="scheduled_time_start" type="time" id="scheduled_time_start" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('scheduled_time_start') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="scheduled_time_end" class="block text-xs text-gray-600">{{ __('End Time') }}</label>
                            <input wire:model="scheduled_time_end" type="time" id="scheduled_time_end" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('scheduled_time_end') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Access Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('Access Information') }}</h3>
                
                <div class="mb-4">
                    <label for="access_instructions" class="block text-sm font-medium text-gray-700">{{ __('Access Instructions') }}</label>
                    <textarea wire:model="access_instructions" id="access_instructions" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                    @error('access_instructions') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <input wire:model="tenant_present_required" type="checkbox" id="tenant_present_required" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <label for="tenant_present_required" class="ml-2 text-sm text-gray-700">{{ __('Tenant presence required') }}</label>
                    </div>

                    <div class="flex items-center">
                        <input wire:model="keys_required" type="checkbox" id="keys_required" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <label for="keys_required" class="ml-2 text-sm text-gray-700">{{ __('Keys required') }}</label>
                    </div>
                </div>
            </div>

            <!-- Cost Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('Cost Estimate') }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="estimated_cost" class="block text-sm font-medium text-gray-700">{{ __('Estimated Cost') }}</label>
                        <input wire:model="estimated_cost" type="number" step="0.01" id="estimated_cost" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('estimated_cost') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center">
                        <input wire:model="cost_approval_required" type="checkbox" id="cost_approval_required" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <label for="cost_approval_required" class="ml-2 text-sm text-gray-700">{{ __('Cost approval required') }}</label>
                    </div>
                </div>
            </div>

            <!-- Recurring Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('Recurring Settings') }}</h3>
                
                <div class="mb-4">
                    <div class="flex items-center">
                        <input wire:model.live="is_recurring" type="checkbox" id="is_recurring" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <label for="is_recurring" class="ml-2 text-sm text-gray-700">{{ __('This is a recurring maintenance request') }}</label>
                    </div>
                </div>

                @if($is_recurring)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="recurring_frequency" class="block text-sm font-medium text-gray-700">{{ __('Frequency') }}</label>
                        <select wire:model="recurring_frequency" id="recurring_frequency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">{{ __('Select Frequency') }}</option>
                            <option value="weekly">{{ __('Weekly') }}</option>
                            <option value="monthly">{{ __('Monthly') }}</option>
                            <option value="quarterly">{{ __('Quarterly') }}</option>
                            <option value="semi_annual">{{ __('Semi-Annual') }}</option>
                            <option value="annual">{{ __('Annual') }}</option>
                        </select>
                        @error('recurring_frequency') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="next_due_date" class="block text-sm font-medium text-gray-700">{{ __('Next Due Date') }}</label>
                        <input wire:model="next_due_date" type="date" id="next_due_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('next_due_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                @endif
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded">
                    {{ __('Create Request') }}
                </button>
            </div>
        </form>
    </div>
</div>
