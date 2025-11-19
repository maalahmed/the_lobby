<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Create New Role</h1>
                <p class="mt-1 text-sm text-gray-600">Define a new role with specific permissions</p>
            </div>

            @if (session()->has('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form -->
            <form wire:submit.prevent="save">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <!-- Role Name -->
                    <div class="p-6 border-b border-gray-200">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Role Name <span class="text-red-500">*</span>
                            </label>
                            <input wire:model="name"
                                   type="text"
                                   id="name"
                                   placeholder="e.g., manager, accountant"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Use lowercase, single word names (e.g., manager, not "Property Manager")</p>
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Permissions</h3>
                        <p class="text-sm text-gray-600 mb-6">Select the permissions this role should have. Permissions control what actions users with this role can perform.</p>

                        <div class="space-y-6">
                            @foreach($permissionGroups as $groupName => $permissions)
                                @if($permissions->count() > 0)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center mb-3">
                                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <h4 class="font-semibold text-gray-900">{{ $groupName }}</h4>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                            @foreach($permissions as $permission)
                                                <label class="flex items-center space-x-2 text-sm hover:bg-gray-50 p-2 rounded cursor-pointer">
                                                    <input type="checkbox"
                                                           wire:model="selectedPermissions"
                                                           value="{{ $permission->name }}"
                                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                    <span class="text-gray-700">{{ ucwords(str_replace('-', ' ', $permission->name)) }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        @error('selectedPermissions')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                        <a href="{{ route('admin.roles.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cancel
                        </a>
                        <button type="submit"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <span wire:loading.remove>Create Role</span>
                            <span wire:loading>Creating...</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
