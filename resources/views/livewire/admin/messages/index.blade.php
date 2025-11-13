<div>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Messages') }}</h1>
            <a href="{{ route('admin.messages.create') }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                {{ __('New Message') }}
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <input type="text" 
                           wire:model.live="search" 
                           placeholder="{{ __('Search messages...') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <select wire:model.live="filterType" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('All Messages') }}</option>
                        <option value="sent">{{ __('Sent') }}</option>
                        <option value="archived">{{ __('Archived') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('From') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('To') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Subject') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Date') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($messages as $message)
                        <tr class="hover:bg-gray-50 {{ !$message->is_read ? 'bg-blue-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $message->sender->name ?? __('Unknown') }}</div>
                                <div class="text-xs text-gray-500">{{ $message->sender->email ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $message->recipient->name ?? __('Unknown') }}</div>
                                <div class="text-xs text-gray-500">{{ $message->recipient->email ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 {{ !$message->is_read ? 'font-semibold' : '' }}">
                                    {{ $message->subject }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ Str::limit($message->body, 60) }}
                                </div>
                                @if($message->replies_count > 0)
                                    <div class="text-xs text-blue-600 mt-1">
                                        {{ $message->replies_count }} {{ __('replies') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(!$message->is_read)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ __('Unread') }}
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ __('Read') }}
                                    </span>
                                @endif
                                @if($message->is_archived_by_sender || $message->is_archived_by_recipient)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 ml-1">
                                        {{ __('Archived') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $message->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.messages.show', $message) }}" 
                                   class="text-blue-600 hover:text-blue-900">{{ __('View') }}</a>
                                <a href="{{ route('admin.messages.edit', $message) }}" 
                                   class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                                <button wire:click="archive({{ $message->id }})" 
                                        class="text-yellow-600 hover:text-yellow-900"
                                        wire:confirm="{{ __('Archive this message?') }}">
                                    {{ __('Archive') }}
                                </button>
                                <button wire:click="delete({{ $message->id }})" 
                                        class="text-red-600 hover:text-red-900"
                                        wire:confirm="{{ __('Are you sure you want to delete this message?') }}">
                                    {{ __('Delete') }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                {{ __('No messages found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $messages->links() }}
        </div>
    </div>
</div>
