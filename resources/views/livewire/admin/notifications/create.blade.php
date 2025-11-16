<div>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('admin.notifications.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-2xl font-semibold text-gray-900">{{ __('New Notification') }}</h1>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form wire:submit.prevent="save">
            <div class="space-y-6">
                <!-- User -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">
                        {{ __('User') }} <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="user_id"
                            id="user_id"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Select user') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">
                        {{ __('Type') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           wire:model="type"
                           id="type"
                           placeholder="e.g., payment_reminder, maintenance_update"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">
                        {{ __('Title') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           wire:model="title"
                           id="title"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Message -->
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700">
                        {{ __('Message') }} <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="message"
                              id="message"
                              rows="4"
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    @error('message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Priority & Action -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700">
                            {{ __('Priority') }} <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="priority"
                                id="priority"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="low">{{ __('Low') }}</option>
                            <option value="normal">{{ __('Normal') }}</option>
                            <option value="high">{{ __('High') }}</option>
                            <option value="urgent">{{ __('Urgent') }}</option>
                        </select>
                        @error('priority') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="action_url" class="block text-sm font-medium text-gray-700">
                            {{ __('Action URL') }}
                        </label>
                        <input type="url"
                               wire:model="action_url"
                               id="action_url"
                               placeholder="https://..."
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('action_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Is Actionable -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="is_actionable" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">{{ __('Is Actionable (requires user action)') }}</span>
                    </label>
                    @error('is_actionable') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Channels -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Channels') }}
                    </label>
                    <div class="flex flex-wrap gap-4">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="channels" value="database" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ __('Database') }}</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="channels" value="email" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ __('Email') }}</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="channels" value="sms" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ __('SMS') }}</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="channels" value="push" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ __('Push') }}</span>
                        </label>
                    </div>
                    @error('channels') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Data (JSON) -->
                <div>
                    <label for="data" class="block text-sm font-medium text-gray-700">
                        {{ __('Additional Data (JSON)') }}
                    </label>
                    <textarea wire:model="data"
                              id="data"
                              rows="3"
                              placeholder='{"key": "value"}'
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 font-mono text-sm"></textarea>
                    @error('data') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Notifiable (Optional) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="notifiable_type" class="block text-sm font-medium text-gray-700">
                            {{ __('Related To (Type)') }}
                        </label>
                        <input type="text"
                               wire:model="notifiable_type"
                               id="notifiable_type"
                               placeholder="e.g., Property, Invoice"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('notifiable_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="notifiable_id" class="block text-sm font-medium text-gray-700">
                            {{ __('Related To (ID)') }}
                        </label>
                        <input type="number"
                               wire:model="notifiable_id"
                               id="notifiable_id"
                               placeholder="e.g., 123"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('notifiable_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.notifications.index') }}"
                       class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        {{ __('Send Notification') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
