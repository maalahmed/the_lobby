<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('admin.messages.show', $message) }}" class="text-gray-600 hover:text-gray-900 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-2xl font-semibold text-gray-900">{{ __('Edit Message') }}</h1>
            </div>
            <button wire:click="delete" 
                    wire:confirm="{{ __('Are you sure you want to delete this message?') }}"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                {{ __('Delete') }}
            </button>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form wire:submit.prevent="update">
            <div class="space-y-6">
                <!-- Recipient -->
                <div>
                    <label for="recipient_id" class="block text-sm font-medium text-gray-700">
                        {{ __('Recipient') }} <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="recipient_id" 
                            id="recipient_id"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Select recipient') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error('recipient_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700">
                        {{ __('Subject') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           wire:model="subject" 
                           id="subject"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('subject') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Body -->
                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700">
                        {{ __('Message') }} <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="body" 
                              id="body"
                              rows="8"
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    @error('body') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Context Type (Optional) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="context_type" class="block text-sm font-medium text-gray-700">
                            {{ __('Related To (Type)') }}
                        </label>
                        <input type="text" 
                               wire:model="context_type" 
                               id="context_type"
                               placeholder="e.g., Property, MaintenanceRequest"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('context_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="context_id" class="block text-sm font-medium text-gray-700">
                            {{ __('Related To (ID)') }}
                        </label>
                        <input type="number" 
                               wire:model="context_id" 
                               id="context_id"
                               placeholder="e.g., 123"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('context_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Message Info -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">{{ __('Message Information') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">{{ __('Sender:') }}</span>
                            <span class="ml-2 text-gray-900">{{ $message->sender->name ?? __('Unknown') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">{{ __('Created:') }}</span>
                            <span class="ml-2 text-gray-900">{{ $message->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">{{ __('Status:') }}</span>
                            <span class="ml-2">
                                @if($message->is_read)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ __('Read') }}
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ __('Unread') }}
                                    </span>
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-500">{{ __('Thread ID:') }}</span>
                            <span class="ml-2 text-gray-900">#{{ $message->thread_id ?? $message->id }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.messages.show', $message) }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        {{ __('Update Message') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
    </div>
        </div>
</div>
