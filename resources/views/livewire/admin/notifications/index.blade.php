<div>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Notifications') }}</h1>
            <a href="{{ route('admin.notifications.create') }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                {{ __('New Notification') }}
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <input type="text" 
                           wire:model.live="search" 
                           placeholder="{{ __('Search notifications...') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <select wire:model.live="typeFilter" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('All Types') }}</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select wire:model.live="statusFilter" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('All Status') }}</option>
                        <option value="unread">{{ __('Unread') }}</option>
                        <option value="read">{{ __('Read') }}</option>
                        <option value="sent">{{ __('Sent') }}</option>
                        <option value="failed">{{ __('Failed') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('User') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Type') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Title') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Channels') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Date') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($notifications as $notification)
                        <tr class="hover:bg-gray-50 {{ $notification->isUnread() ? 'bg-blue-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $notification->user->name ?? __('Unknown') }}</div>
                                <div class="text-xs text-gray-500">{{ $notification->user->email ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                    {{ ucfirst($notification->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 {{ $notification->isUnread() ? 'font-semibold' : '' }}">
                                    {{ $notification->title }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ Str::limit($notification->message, 50) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($notification->channels ?? [] as $channel)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ $channel }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($notification->failed_at)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ __('Failed') }}
                                    </span>
                                @elseif($notification->sent_at)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ __('Sent') }}
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ __('Pending') }}
                                    </span>
                                @endif
                                @if($notification->isUnread())
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 ml-1">
                                        {{ __('Unread') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $notification->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.notifications.show', $notification) }}" 
                                   class="text-blue-600 hover:text-blue-900">{{ __('View') }}</a>
                                <a href="{{ route('admin.notifications.edit', $notification) }}" 
                                   class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                                @if($notification->isUnread())
                                    <button wire:click="markAsRead({{ $notification->id }})" 
                                            class="text-green-600 hover:text-green-900">
                                        {{ __('Mark Read') }}
                                    </button>
                                @endif
                                <button wire:click="delete({{ $notification->id }})" 
                                        class="text-red-600 hover:text-red-900"
                                        wire:confirm="{{ __('Are you sure you want to delete this notification?') }}">
                                    {{ __('Delete') }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                {{ __('No notifications found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
