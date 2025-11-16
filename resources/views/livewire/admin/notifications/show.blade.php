<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('admin.notifications.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-2xl font-semibold text-gray-900">{{ __('Notification Details') }}</h1>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.notifications.edit', $notification) }}" 
                   class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    {{ __('Edit') }}
                </a>
                <button wire:click="delete" 
                        wire:confirm="{{ __('Are you sure you want to delete this notification?') }}"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    {{ __('Delete') }}
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Notification Content -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Content') }}</h2>
                
                <!-- English -->
                <div class="mb-6">
                    <div class="flex items-center mb-2">
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">EN</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $notification->title }}</h3>
                    <div class="text-gray-700 prose max-w-none">
                        {!! nl2br(e($notification->message)) !!}
                    </div>
                </div>

                <!-- Arabic -->
                @if($notification->title_ar || $notification->message_ar)
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex items-center mb-2">
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">AR</span>
                        </div>
                        @if($notification->title_ar)
                            <h3 class="text-xl font-semibold text-gray-900 mb-2" dir="rtl">{{ $notification->title_ar }}</h3>
                        @endif
                        @if($notification->message_ar)
                            <div class="text-gray-700 prose max-w-none" dir="rtl">
                                {!! nl2br(e($notification->message_ar)) !!}
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Additional Data -->
            @if($notification->data)
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Additional Data') }}</h2>
                    <div class="bg-gray-50 rounded p-4">
                        <pre class="text-sm text-gray-900 whitespace-pre-wrap font-mono">{{ json_encode($notification->data, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
            @endif

            <!-- Related Item -->
            @if($notification->notifiable_type)
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Related Item') }}</h2>
                    <div class="bg-blue-50 rounded p-4">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">{{ __('Type:') }}</span>
                            {{ class_basename($notification->notifiable_type) }}
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            <span class="font-medium">{{ __('ID:') }}</span>
                            #{{ $notification->notifiable_id }}
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Notification Info -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Information') }}</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Recipient') }}</p>
                        @if($notification->user)
                            <p class="mt-1 text-sm text-gray-900">{{ $notification->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $notification->user->email }}</p>
                        @else
                            <p class="mt-1 text-sm text-gray-400">{{ __('Unknown') }}</p>
                        @endif
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Type') }}</p>
                        <p class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                {{ ucfirst($notification->type) }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Channels') }}</p>
                        <div class="mt-1 flex flex-wrap gap-1">
                            @foreach($notification->channels ?? ['database'] as $channel)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $channel }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Status') }}</p>
                        <div class="mt-1 space-y-1">
                            @if($notification->failed_at)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    {{ __('Failed') }}
                                </span>
                                @if($notification->failure_reason)
                                    <p class="text-xs text-red-600 mt-1">{{ $notification->failure_reason }}</p>
                                @endif
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
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ __('Unread') }}
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ __('Read') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Created') }}</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $notification->created_at->format('Y-m-d H:i:s') }}</p>
                        <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>

                    @if($notification->sent_at)
                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ __('Sent At') }}</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $notification->sent_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    @endif

                    @if($notification->read_at)
                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ __('Read At') }}</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $notification->read_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    @endif

                    @if($notification->failed_at)
                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ __('Failed At') }}</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $notification->failed_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
        </div>
</div>
