<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.audit-logs.index') }}" class="text-blue-600 hover:text-blue-700 mb-4 inline-flex items-center font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ __('Back to Audit Logs') }}
            </a>
            <h1 class="text-2xl font-semibold text-gray-800 mt-4">{{ __('Audit Log Details') }}</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Event Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Event Information') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Event') }}</p>
                            <p class="mt-1">
                                @if($log->event === 'created')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700 border border-green-200">
                                        {{ __('Created') }}
                                    </span>
                                @elseif($log->event === 'updated')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700 border border-blue-200">
                                        {{ __('Updated') }}
                                    </span>
                                @elseif($log->event === 'deleted')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700 border border-red-200">
                                        {{ __('Deleted') }}
                                    </span>
                                @elseif($log->event === 'viewed')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-700 border border-purple-200">
                                        {{ __('Viewed') }}
                                    </span>
                                @elseif($log->event === 'exported')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-700 border border-orange-200">
                                        {{ __('Exported') }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700 border border-gray-200">
                                        {{ ucfirst($log->event) }}
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Model') }}</p>
                            <p class="mt-1 text-sm text-gray-900">{{ class_basename($log->auditable_type) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Model ID') }}</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $log->auditable_id }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Date') }}</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $log->created_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $log->created_at->format('H:i:s') }}</p>
                        </div>
                    </div>
                </div>

                <!-- User & Request Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('User & Request') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('User') }}</p>
                            @if($log->user)
                                <p class="mt-1 text-sm font-medium text-gray-900">{{ $log->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $log->user->email }}</p>
                            @else
                                <p class="mt-1 text-sm text-gray-400 italic">{{ __('System') }}</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('IP Address') }}</p>
                            <p class="mt-1 text-sm text-gray-900 font-mono">{{ $log->ip_address ?? 'N/A' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-600">{{ __('User Agent') }}</p>
                            <p class="mt-1 text-sm text-gray-700 break-all">{{ $log->user_agent ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Changes -->
                @if($log->old_values || $log->new_values)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Changes') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($log->old_values)
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-2">{{ __('Old Values') }}</p>
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                        <pre class="text-xs text-gray-900 whitespace-pre-wrap font-mono">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                </div>
                            @endif
                            @if($log->new_values)
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-2">{{ __('New Values') }}</p>
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                        <pre class="text-xs text-gray-900 whitespace-pre-wrap font-mono">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Metadata -->
                @if($log->metadata)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Metadata') }}</h2>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <pre class="text-sm text-gray-900 whitespace-pre-wrap font-mono">{{ json_encode($log->metadata, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Quick Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Quick Info') }}</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Log ID') }}</p>
                            <p class="mt-1 text-sm text-gray-900 font-mono">#{{ $log->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Timestamp') }}</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $log->created_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $log->created_at->format('H:i:s') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Time Ago') }}</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
        </div>
</div>
