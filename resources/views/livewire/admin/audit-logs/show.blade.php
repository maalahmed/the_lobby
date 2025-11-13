<div>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('admin.audit-logs.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-2xl font-semibold text-gray-900">{{ __('Audit Log Details') }}</h1>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Event Information -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Event Information') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Event') }}</p>
                        <p class="mt-1">
                            @if($log->event === 'created')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ __('Created') }}
                                </span>
                            @elseif($log->event === 'updated')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ __('Updated') }}
                                </span>
                            @elseif($log->event === 'deleted')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    {{ __('Deleted') }}
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($log->event) }}
                                </span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Model') }}</p>
                        <p class="mt-1 text-sm text-gray-900">{{ class_basename($log->auditable_type) }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Model ID') }}</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $log->auditable_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Date') }}</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $log->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>
            </div>

            <!-- User & Request Information -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('User & Request') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('User') }}</p>
                        @if($log->user)
                            <p class="mt-1 text-sm text-gray-900">{{ $log->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $log->user->email }}</p>
                        @else
                            <p class="mt-1 text-sm text-gray-400">{{ __('System') }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('IP Address') }}</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $log->ip_address ?? 'N/A' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-500">{{ __('User Agent') }}</p>
                        <p class="mt-1 text-sm text-gray-900 break-all">{{ $log->user_agent ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Changes -->
            @if($log->old_values || $log->new_values)
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Changes') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($log->old_values)
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-2">{{ __('Old Values') }}</p>
                                <div class="bg-red-50 rounded p-4">
                                    <pre class="text-xs text-gray-900 whitespace-pre-wrap font-mono">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        @endif
                        @if($log->new_values)
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-2">{{ __('New Values') }}</p>
                                <div class="bg-green-50 rounded p-4">
                                    <pre class="text-xs text-gray-900 whitespace-pre-wrap font-mono">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Metadata -->
            @if($log->metadata)
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Metadata') }}</h2>
                    <div class="bg-gray-50 rounded p-4">
                        <pre class="text-sm text-gray-900 whitespace-pre-wrap font-mono">{{ json_encode($log->metadata, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Info -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Quick Info') }}</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Log ID') }}</p>
                        <p class="mt-1 text-sm text-gray-900">#{{ $log->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Timestamp') }}</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $log->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
