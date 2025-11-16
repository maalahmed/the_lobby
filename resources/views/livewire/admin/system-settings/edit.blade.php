@section('header')
    Edit System Setting
@endsection

<div>
    <div class="mb-6">
        <div class="flex items-center">
            <a href="{{ route('admin.system-settings.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Edit System Setting') }}</h1>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form wire:submit.prevent="update">
            <!-- Basic Information -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Basic Information') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Key') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="key"
                               placeholder="app.name"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('key') border-red-500 @enderror">
                        @error('key')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Type') }} <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="type"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror">
                            <option value="string">{{ __('String') }}</option>
                            <option value="integer">{{ __('Integer') }}</option>
                            <option value="boolean">{{ __('Boolean') }}</option>
                            <option value="json">{{ __('JSON') }}</option>
                            <option value="array">{{ __('Array') }}</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Value') }}</label>
                        <textarea wire:model="value"
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('value') border-red-500 @enderror"></textarea>
                        @error('value')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Organization -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Organization') }}</h2>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Group') }}</label>
                        <select wire:model="group"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('group') border-red-500 @enderror">
                            <option value="">{{ __('Select Group') }}</option>
                            <option value="general">{{ __('General') }}</option>
                            <option value="email">{{ __('Email') }}</option>
                            <option value="payment">{{ __('Payment') }}</option>
                            <option value="notifications">{{ __('Notifications') }}</option>
                        </select>
                        @error('group')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Description') }}</label>
                        <textarea wire:model="description"
                                  rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Permissions -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Permissions') }}</h2>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input type="checkbox"
                               wire:model="is_public"
                               id="is_public"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_public" class="ml-2 block text-sm text-gray-700">
                            {{ __('Public (accessible to frontend)') }}
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox"
                               wire:model="is_editable"
                               id="is_editable"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_editable" class="ml-2 block text-sm text-gray-700">
                            {{ __('Editable (can be modified/deleted)') }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('admin.system-settings.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Cancel') }}
                </a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Update Setting') }}
                </button>
            </div>
        </form>
    </div>
</div>
