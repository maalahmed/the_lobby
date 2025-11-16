@section('header')
    System Settings
@endsection

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">{{ __('System Settings') }}</h1>
                <p class="mt-2 text-sm text-gray-600">
                    {{ __('Manage application configuration and preferences') }}
                </p>
            </div>
            <a href="{{ route('admin.system-settings.create') }}"
               class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('Add Setting') }}
            </a>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Search') }}</label>
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="{{ __('Search by key, value, description...') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-700 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Group') }}</label>
                    <select wire:model.live="groupFilter"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-700 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('All Groups') }}</option>
                        <option value="general">{{ __('General') }}</option>
                        <option value="email">{{ __('Email') }}</option>
                        <option value="payment">{{ __('Payment') }}</option>
                        <option value="notifications">{{ __('Notifications') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Settings Table -->
        <!-- Settings Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Key') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Value') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Type') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Group') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($settings as $setting)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 font-mono">{{ $setting->key }}</div>
                                    @if($setting->description)
                                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($setting->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">
                                        {{ Str::limit($setting->value ?? 'N/A', 60) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700 border border-gray-200">
                                        {{ $setting->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($setting->group)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($setting->group === 'general') bg-blue-100 text-blue-700 border border-blue-200
                                            @elseif($setting->group === 'email') bg-green-100 text-green-700 border border-green-200
                                            @elseif($setting->group === 'payment') bg-purple-100 text-purple-700 border border-purple-200
                                            @elseif($setting->group === 'notifications') bg-orange-100 text-orange-700 border border-orange-200
                                            @else bg-gray-100 text-gray-700 border border-gray-200
                                            @endif">
                                            {{ ucfirst($setting->group) }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        @if($setting->is_public)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700 border border-green-200">
                                                {{ __('Public') }}
                                            </span>
                                        @endif
                                        @if(!$setting->is_editable)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700 border border-red-200">
                                                {{ __('Locked') }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.system-settings.show', $setting) }}"
                                       class="text-blue-600 hover:text-blue-700 mr-3 transition-colors">{{ __('View') }}</a>
                                    @if($setting->is_editable)
                                        <a href="{{ route('admin.system-settings.edit', $setting) }}"
                                           class="text-indigo-600 hover:text-indigo-700 mr-3 transition-colors">{{ __('Edit') }}</a>
                                        <button wire:click="delete({{ $setting->id }})"
                                                wire:confirm="Are you sure you want to delete this setting?"
                                                class="text-red-600 hover:text-red-700 transition-colors">
                                            {{ __('Delete') }}
                                        </button>
                                    @else
                                        <span class="text-gray-400">{{ __('Locked') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">{{ __('No system settings found.') }}</p>
                                    <p class="text-xs text-gray-400">{{ __('Try adjusting your filters or create a new setting') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($settings->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $settings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
