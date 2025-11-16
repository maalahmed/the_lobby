<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('admin.notifications.show', $notification) }}" class="text-gray-600 hover:text-gray-900 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-2xl font-semibold text-gray-900">{{ __('Edit Notification') }}</h1>
            </div>
            <button wire:click="delete" 
                    wire:confirm="{{ __('Are you sure you want to delete this notification?') }}"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                {{ __('Delete') }}
            </button>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form wire:submit.prevent="update">
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

                <!-- Title (English & Arabic) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">
                            {{ __('Title (English)') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               wire:model="title" 
                               id="title"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="title_ar" class="block text-sm font-medium text-gray-700">
                            {{ __('Title (Arabic)') }}
                        </label>
                        <input type="text" 
                               wire:model="title_ar" 
                               id="title_ar"
                               dir="rtl"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('title_ar') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Message (English & Arabic) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700">
                            {{ __('Message (English)') }} <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="message" 
                                  id="message"
                                  rows="4"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                        @error('message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="message_ar" class="block text-sm font-medium text-gray-700">
                            {{ __('Message (Arabic)') }}
                        </label>
                        <textarea wire:model="message_ar" 
                                  id="message_ar"
                                  rows="4"
                                  dir="rtl"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                        @error('message_ar') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
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

                <!-- Current Status -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">{{ __('Current Status') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">{{ __('Created:') }}</span>
                            <span class="ml-2 text-gray-900">{{ $notification->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        @if($notification->sent_at)
                            <div>
                                <span class="text-gray-500">{{ __('Sent:') }}</span>
                                <span class="ml-2 text-gray-900">{{ $notification->sent_at->format('Y-m-d H:i') }}</span>
                            </div>
                        @endif
                        @if($notification->read_at)
                            <div>
                                <span class="text-gray-500">{{ __('Read:') }}</span>
                                <span class="ml-2 text-gray-900">{{ $notification->read_at->format('Y-m-d H:i') }}</span>
                            </div>
                        @endif
                        @if($notification->failed_at)
                            <div>
                                <span class="text-gray-500">{{ __('Failed:') }}</span>
                                <span class="ml-2 text-red-600">{{ $notification->failed_at->format('Y-m-d H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.notifications.show', $notification) }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        {{ __('Update Notification') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
    </div>
        </div>
</div>
