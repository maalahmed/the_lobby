<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">{{ __('Request') }}: {{ $request->request_number }}</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.maintenance-requests.edit', $request) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Edit') }}
            </a>
            @if(!$request->jobs()->exists())
            <button wire:click="delete" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Delete') }}
            </button>
            @endif
            <a href="{{ route('admin.maintenance-requests.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Back') }}
            </a>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Request Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    {{ __('Request Information') }}
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $request->title }}</h3>
                        <p class="text-sm text-gray-600 mt-2">{{ $request->description }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Category') }}</p>
                            <p class="font-medium">{{ __(ucfirst(str_replace('_', ' ', $request->category))) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Priority') }}</p>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                @if($request->priority === 'urgent') bg-red-100 text-red-800
                                @elseif($request->priority === 'high') bg-orange-100 text-orange-800
                                @elseif($request->priority === 'normal') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ __(ucfirst($request->priority)) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Status') }}</p>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                @if($request->status === 'completed') bg-green-100 text-green-800
                                @elseif($request->status === 'in_progress') bg-blue-100 text-blue-800
                                @elseif($request->status === 'assigned') bg-purple-100 text-purple-800
                                @elseif($request->status === 'approved') bg-indigo-100 text-indigo-800
                                @elseif($request->status === 'on_hold') bg-yellow-100 text-yellow-800
                                @elseif($request->status === 'cancelled') bg-gray-100 text-gray-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ __(ucfirst(str_replace('_', ' ', $request->status))) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Created') }}</p>
                            <p class="font-medium">{{ $request->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('Location') }}</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Property') }}</p>
                            <p class="font-medium">{{ $request->property->name }}</p>
                            <p class="text-sm text-gray-500">{{ $request->property->address }}</p>
                        </div>
                        <a href="{{ route('admin.properties.show', $request->property) }}" class="text-indigo-600 hover:text-indigo-900">
                            {{ __('View') }}
                        </a>
                    </div>

                    @if($request->unit)
                    <div class="p-3 bg-gray-50 rounded">
                        <p class="text-sm text-gray-600">{{ __('Unit') }}</p>
                        <p class="font-medium">{{ $request->unit->unit_number }} - {{ $request->unit->type }}</p>
                    </div>
                    @endif

                    @if($request->tenant)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Tenant') }}</p>
                            <p class="font-medium">{{ $request->tenant->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $request->tenant->user->email }}</p>
                        </div>
                        <a href="{{ route('admin.tenants.show', $request->tenant) }}" class="text-indigo-600 hover:text-indigo-900">
                            {{ __('View') }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Scheduling -->
            @if($request->preferred_date || $request->scheduled_date)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('Scheduling') }}</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($request->preferred_date)
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded">
                        <h3 class="text-sm font-semibold text-blue-800 mb-2">{{ __('Preferred Time') }}</h3>
                        <p class="text-blue-900">{{ $request->preferred_date->format('F d, Y') }}</p>
                        @if($request->preferred_time_start)
                        <p class="text-sm text-blue-700">{{ $request->preferred_time_start }} - {{ $request->preferred_time_end }}</p>
                        @endif
                    </div>
                    @endif

                    @if($request->scheduled_date)
                    <div class="p-4 bg-green-50 border border-green-200 rounded">
                        <h3 class="text-sm font-semibold text-green-800 mb-2">{{ __('Scheduled Time') }}</h3>
                        <p class="text-green-900">{{ $request->scheduled_date->format('F d, Y') }}</p>
                        @if($request->scheduled_time_start)
                        <p class="text-sm text-green-700">{{ $request->scheduled_time_start }} - {{ $request->scheduled_time_end }}</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Access Information -->
            @if($request->access_instructions || $request->tenant_present_required || $request->keys_required)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('Access Information') }}</h2>
                
                @if($request->access_instructions)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-1">{{ __('Instructions') }}</p>
                    <p class="text-gray-700">{{ $request->access_instructions }}</p>
                </div>
                @endif

                <div class="flex gap-4">
                    @if($request->tenant_present_required)
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm">{{ __('Tenant presence required') }}</span>
                    </div>
                    @endif

                    @if($request->keys_required)
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm">{{ __('Keys required') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Cost Information -->
            @if($request->estimated_cost || $request->approved_cost || $request->final_cost)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('Cost Information') }}</h2>
                
                <div class="grid grid-cols-3 gap-4">
                    @if($request->estimated_cost)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Estimated') }}</p>
                        <p class="text-lg font-semibold">{{ number_format((float)$request->estimated_cost, 2) }} SAR</p>
                    </div>
                    @endif

                    @if($request->approved_cost)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Approved') }}</p>
                        <p class="text-lg font-semibold text-green-600">{{ number_format((float)$request->approved_cost, 2) }} SAR</p>
                        @if($request->costApprover)
                        <p class="text-xs text-gray-500">{{ __('by') }} {{ $request->costApprover->name }}</p>
                        @endif
                    </div>
                    @endif

                    @if($request->final_cost)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Final') }}</p>
                        <p class="text-lg font-semibold text-blue-600">{{ number_format((float)$request->final_cost, 2) }} SAR</p>
                    </div>
                    @endif
                </div>

                @if($request->cost_approval_required && !$request->cost_approved_at)
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded">
                    <p class="text-sm text-yellow-800">{{ __('Cost approval required') }}</p>
                </div>
                @endif
            </div>
            @endif

            <!-- Completion -->
            @if($request->status === 'completed')
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('Completion Details') }}</h2>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Completed At') }}</p>
                        <p class="font-medium">{{ $request->completed_at->format('F d, Y H:i') }}</p>
                    </div>

                    @if($request->completion_notes)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Completion Notes') }}</p>
                        <p class="text-gray-700">{{ $request->completion_notes }}</p>
                    </div>
                    @endif

                    @if($request->tenant_satisfaction_rating)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">{{ __('Tenant Rating') }}</p>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $request->tenant_satisfaction_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                            <span class="ml-2 text-sm text-gray-600">({{ $request->tenant_satisfaction_rating }}/5)</span>
                        </div>
                    </div>
                    @endif

                    @if($request->tenant_feedback)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Tenant Feedback') }}</p>
                        <p class="text-gray-700 italic">"{{ $request->tenant_feedback }}"</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- People -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('People') }}</h2>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Requested By') }}</p>
                        <p class="font-medium">{{ $request->requester->name }}</p>
                        <p class="text-xs text-gray-500">{{ $request->created_at->format('M d, Y') }}</p>
                    </div>

                    @if($request->assignee)
                    <div class="pt-3 border-t">
                        <p class="text-sm text-gray-600">{{ __('Assigned To') }}</p>
                        <p class="font-medium">{{ $request->assignee->name }}</p>
                    </div>
                    @else
                    <div class="pt-3 border-t">
                        <p class="text-sm text-gray-500">{{ __('Not assigned yet') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recurring -->
            @if($request->is_recurring)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('Recurring') }}</h2>
                
                <div class="space-y-2">
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Frequency') }}</p>
                        <p class="font-medium">{{ __(ucfirst(str_replace('_', ' ', $request->recurring_frequency))) }}</p>
                    </div>

                    @if($request->next_due_date)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Next Due') }}</p>
                        <p class="font-medium">{{ $request->next_due_date->format('M d, Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Jobs -->
            @if($request->jobs->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('Related Jobs') }} ({{ $request->jobs->count() }})</h2>
                
                <div class="space-y-2">
                    @foreach($request->jobs as $job)
                    <div class="p-3 bg-gray-50 rounded hover:bg-gray-100">
                        <p class="text-sm font-medium">{{ $job->title }}</p>
                        <p class="text-xs text-gray-500">{{ $job->created_at->format('M d, Y') }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
