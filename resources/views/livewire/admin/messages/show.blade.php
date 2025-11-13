<div>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('admin.messages.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-2xl font-semibold text-gray-900">{{ $message->subject }}</h1>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.messages.edit', $message) }}" 
                   class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    {{ __('Edit') }}
                </a>
                <button wire:click="delete" 
                        wire:confirm="{{ __('Are you sure you want to delete this message?') }}"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    {{ __('Delete') }}
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Original Message -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold">
                            {{ substr($message->sender->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $message->sender->name ?? __('Unknown') }}</p>
                            <p class="text-xs text-gray-500">{{ $message->sender->email ?? '' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-900">{{ $message->created_at->format('Y-m-d H:i') }}</p>
                        <p class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <div class="mb-3">
                        <span class="text-sm text-gray-500">{{ __('To:') }}</span>
                        <span class="text-sm text-gray-900 ml-2">{{ $message->recipient->name ?? __('Unknown') }}</span>
                    </div>
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($message->body)) !!}
                    </div>

                    @if($message->attachments)
                        <div class="mt-4 p-3 bg-gray-50 rounded">
                            <p class="text-sm font-medium text-gray-700 mb-2">{{ __('Attachments:') }}</p>
                            <ul class="text-sm text-gray-600">
                                @foreach($message->attachments as $attachment)
                                    <li>{{ $attachment }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($message->context_type)
                        <div class="mt-4 p-3 bg-blue-50 rounded">
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">{{ __('Related to:') }}</span>
                                {{ class_basename($message->context_type) }} #{{ $message->context_id }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Replies -->
            @if($message->replies->count() > 0)
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('Replies') }} ({{ $message->replies->count() }})</h3>
                    @foreach($message->replies as $reply)
                        <div class="bg-white shadow-md rounded-lg p-6 ml-8">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-green-600 flex items-center justify-center text-white text-sm font-semibold">
                                        {{ substr($reply->sender->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $reply->sender->name ?? __('Unknown') }}</p>
                                        <p class="text-xs text-gray-500">{{ $reply->created_at->format('Y-m-d H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="text-sm text-gray-700">
                                {!! nl2br(e($reply->body)) !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Reply Form -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Send Reply') }}</h3>
                <form wire:submit.prevent="sendReply">
                    <div>
                        <textarea wire:model="replyBody" 
                                  rows="4"
                                  placeholder="{{ __('Type your reply...') }}"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                        @error('replyBody') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            {{ __('Send Reply') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Message Info -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Message Info') }}</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Status') }}</p>
                        <p class="mt-1">
                            @if($message->is_read)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ __('Read') }}
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ __('Unread') }}
                                </span>
                            @endif
                        </p>
                    </div>

                    @if($message->read_at)
                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ __('Read At') }}</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $message->read_at->format('Y-m-d H:i') }}</p>
                        </div>
                    @endif

                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Thread ID') }}</p>
                        <p class="mt-1 text-sm text-gray-900">#{{ $message->thread_id ?? $message->id }}</p>
                    </div>

                    @if($message->is_archived_by_sender || $message->is_archived_by_recipient)
                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ __('Archived') }}</p>
                            <p class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    {{ __('Yes') }}
                                </span>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
