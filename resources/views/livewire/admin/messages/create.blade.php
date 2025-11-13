<div>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('admin.messages.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-2xl font-semibold text-gray-900">{{ __('New Message') }}</h1>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form wire:submit.prevent="save">
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

                <!-- Actions -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.messages.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        {{ __('Send Message') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
