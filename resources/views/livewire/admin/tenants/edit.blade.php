<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">{{ __('Edit Tenant') }}</h2>
                        <a href="{{ route('admin.tenants.show', $tenant) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Back to Tenant') }}
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
                        <!-- User Information (Read-only) -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">{{ __('User Information') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $tenant->user->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $tenant->user->email }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Phone') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $tenant->user->phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tenant Information -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Tenant Information') }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="occupation" class="block text-sm font-medium text-gray-700">{{ __('Occupation') }}</label>
                                    <input wire:model="occupation" type="text" id="occupation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('occupation') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="employer" class="block text-sm font-medium text-gray-700">{{ __('Employer') }}</label>
                                    <input wire:model="employer" type="text" id="employer" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('employer') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label for="monthly_income" class="block text-sm font-medium text-gray-700">{{ __('Monthly Income') }}</label>
                                    <input wire:model="monthly_income" type="number" step="0.01" id="monthly_income" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('monthly_income') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="credit_score" class="block text-sm font-medium text-gray-700">{{ __('Credit Score') }}</label>
                                    <input wire:model="credit_score" type="number" id="credit_score" min="300" max="850" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('credit_score') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="background_check_status" class="block text-sm font-medium text-gray-700">{{ __('Background Check') }} <span class="text-red-500">*</span></label>
                                    <select wire:model="background_check_status" id="background_check_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="not_required">{{ __('Not Required') }}</option>
                                        <option value="pending">{{ __('Pending') }}</option>
                                        <option value="passed">{{ __('Passed') }}</option>
                                        <option value="failed">{{ __('Failed') }}</option>
                                    </select>
                                    @error('background_check_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Status') }} <span class="text-red-500">*</span></label>
                                <select wire:model="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="prospect">{{ __('Prospect') }}</option>
                                    <option value="active">{{ __('Active') }}</option>
                                    <option value="inactive">{{ __('Inactive') }}</option>
                                    <option value="blacklisted">{{ __('Blacklisted') }}</option>
                                </select>
                                @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Emergency Contact') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="emergency_name" class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                                    <input wire:model="emergency_name" type="text" id="emergency_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('emergency_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="emergency_phone" class="block text-sm font-medium text-gray-700">{{ __('Phone') }}</label>
                                    <input wire:model="emergency_phone" type="text" id="emergency_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('emergency_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="emergency_relationship" class="block text-sm font-medium text-gray-700">{{ __('Relationship') }}</label>
                                    <input wire:model="emergency_relationship" type="text" id="emergency_relationship" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('emergency_relationship') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- References -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('References') }}</h3>
                            
                            <!-- Reference 1 -->
                            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                                <h4 class="text-sm font-semibold mb-3">{{ __('Reference 1') }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="reference1_name" class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                                        <input wire:model="reference1_name" type="text" id="reference1_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @error('reference1_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="reference1_phone" class="block text-sm font-medium text-gray-700">{{ __('Phone') }}</label>
                                        <input wire:model="reference1_phone" type="text" id="reference1_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @error('reference1_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="reference1_relationship" class="block text-sm font-medium text-gray-700">{{ __('Relationship') }}</label>
                                        <input wire:model="reference1_relationship" type="text" id="reference1_relationship" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @error('reference1_relationship') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Reference 2 -->
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h4 class="text-sm font-semibold mb-3">{{ __('Reference 2') }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="reference2_name" class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                                        <input wire:model="reference2_name" type="text" id="reference2_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @error('reference2_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="reference2_phone" class="block text-sm font-medium text-gray-700">{{ __('Phone') }}</label>
                                        <input wire:model="reference2_phone" type="text" id="reference2_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @error('reference2_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="reference2_relationship" class="block text-sm font-medium text-gray-700">{{ __('Relationship') }}</label>
                                        <input wire:model="reference2_relationship" type="text" id="reference2_relationship" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @error('reference2_relationship') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700">{{ __('Notes') }}</label>
                            <textarea wire:model="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                            @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                {{ __('Update Tenant') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
        </div>
</div>
