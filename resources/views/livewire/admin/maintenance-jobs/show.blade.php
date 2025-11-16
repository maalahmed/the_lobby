<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Job Details') }} - {{ $job->job_number }}</h1>
            <p class="mt-1 text-sm text-gray-600">{{ __('Created on') }} {{ $job->created_at->format('M d, Y') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.maintenance-jobs.edit', $job) }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                {{ __('Edit Job') }}
            </a>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Job Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('Job Information') }}</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Job Number') }}</p>
                        <p class="font-medium">{{ $job->job_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Status') }}</p>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($job->status === 'completed') bg-green-100 text-green-800
                            @elseif($job->status === 'in_progress') bg-blue-100 text-blue-800
                            @elseif($job->status === 'accepted') bg-purple-100 text-purple-800
                            @elseif($job->status === 'assigned') bg-yellow-100 text-yellow-800
                            @elseif($job->status === 'rejected') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ __(ucfirst(str_replace('_', ' ', $job->status))) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Maintenance Request -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('Maintenance Request') }}</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Request Number') }}</p>
                            <p class="font-medium">{{ $job->maintenanceRequest->request_number }}</p>
                            <p class="text-sm text-gray-500">{{ $job->maintenanceRequest->title }}</p>
                        </div>
                        <a href="{{ route('admin.maintenance-requests.show', $job->maintenanceRequest) }}" 
                           class="text-indigo-600 hover:text-indigo-900">
                            {{ __('View') }}
                        </a>
                    </div>

                    <div class="p-3 bg-gray-50 rounded">
                        <p class="text-sm text-gray-600">{{ __('Property') }}</p>
                        <p class="font-medium">{{ $job->maintenanceRequest->property->name }}</p>
                        @if($job->maintenanceRequest->unit)
                            <p class="text-sm text-gray-500">{{ __('Unit') }} {{ $job->maintenanceRequest->unit->unit_number }}</p>
                        @endif
                    </div>

                    @if($job->maintenanceRequest->tenant)
                    <div class="p-3 bg-gray-50 rounded">
                        <p class="text-sm text-gray-600">{{ __('Tenant') }}</p>
                        <p class="font-medium">{{ $job->maintenanceRequest->tenant->user->name ?? __('N/A') }}</p>
                        <p class="text-sm text-gray-500">{{ $job->maintenanceRequest->tenant->user->email ?? '' }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Work Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('Work Details') }}</h2>
                <div>
                    <p class="text-sm text-gray-600">{{ __('Description') }}</p>
                    <p class="mt-1 text-gray-900 whitespace-pre-wrap">{{ $job->work_description }}</p>
                </div>
            </div>

            <!-- Schedule -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('Schedule') }}</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Scheduled Date') }}</p>
                        <p class="font-medium">{{ $job->scheduled_date->format('M d, Y') }}</p>
                    </div>
                    @if($job->scheduled_time_start)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Scheduled Time') }}</p>
                        <p class="font-medium">{{ $job->scheduled_time_start->format('H:i') }} - {{ $job->scheduled_time_end?->format('H:i') ?? __('N/A') }}</p>
                    </div>
                    @endif
                    @if($job->started_at)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Started At') }}</p>
                        <p class="font-medium">{{ $job->started_at->format('M d, Y H:i') }}</p>
                    </div>
                    @endif
                    @if($job->completed_at)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Completed At') }}</p>
                        <p class="font-medium">{{ $job->completed_at->format('M d, Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Financial Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('Financial Details') }}</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @if($job->quoted_amount)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Quoted Amount') }}</p>
                        <p class="text-lg font-semibold text-gray-900">{{ number_format((float)$job->quoted_amount, 2) }} SAR</p>
                    </div>
                    @endif
                    
                    @if($job->final_amount)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Final Amount') }}</p>
                        <p class="text-lg font-semibold text-green-600">{{ number_format((float)$job->final_amount, 2) }} SAR</p>
                    </div>
                    @endif

                    <div>
                        <p class="text-sm text-gray-600">{{ __('Payment Status') }}</p>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($job->payment_status === 'paid') bg-green-100 text-green-800
                            @elseif($job->payment_status === 'approved') bg-blue-100 text-blue-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ __(ucfirst($job->payment_status)) }}
                        </span>
                    </div>

                    @if($job->payment_due_date)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Payment Due Date') }}</p>
                        <p class="font-medium">{{ $job->payment_due_date->format('M d, Y') }}</p>
                    </div>
                    @endif

                    @if($job->paid_at)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Paid At') }}</p>
                        <p class="font-medium">{{ $job->paid_at->format('M d, Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quality Rating -->
            @if($job->quality_rating || $job->quality_notes)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('Quality Assessment') }}</h2>
                @if($job->quality_rating)
                <div class="mb-3">
                    <p class="text-sm text-gray-600">{{ __('Rating') }}</p>
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $job->quality_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                        <span class="ml-2 text-sm text-gray-600">({{ $job->quality_rating }}/5)</span>
                    </div>
                </div>
                @endif
                @if($job->quality_notes)
                <div>
                    <p class="text-sm text-gray-600">{{ __('Notes') }}</p>
                    <p class="mt-1 text-gray-900 whitespace-pre-wrap">{{ $job->quality_notes }}</p>
                </div>
                @endif
            </div>
            @endif

            <!-- Additional Notes -->
            @if($job->notes)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('Additional Notes') }}</h2>
                <p class="text-gray-900 whitespace-pre-wrap">{{ $job->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Service Provider -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('Service Provider') }}</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Company') }}</p>
                        <p class="font-medium">{{ $job->serviceProvider->company_name }}</p>
                    </div>
                    @if($job->serviceProvider->primary_contact_name)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Contact Person') }}</p>
                        <p class="font-medium">{{ $job->serviceProvider->primary_contact_name }}</p>
                    </div>
                    @endif
                    @if($job->serviceProvider->primary_contact_phone)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Phone') }}</p>
                        <p class="font-medium">{{ $job->serviceProvider->primary_contact_phone }}</p>
                    </div>
                    @endif
                    @if($job->serviceProvider->primary_contact_email)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Email') }}</p>
                        <p class="font-medium text-sm">{{ $job->serviceProvider->primary_contact_email }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Assignment Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('Assignment') }}</h2>
                <div class="space-y-3">
                    @if($job->assigner)
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Assigned By') }}</p>
                        <p class="font-medium">{{ $job->assigner->name }}</p>
                        <p class="text-xs text-gray-500">{{ $job->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('Actions') }}</h2>
                <div class="space-y-2">
                    <a href="{{ route('admin.maintenance-jobs.edit', $job) }}" 
                       class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        {{ __('Edit Job') }}
                    </a>
                    <button wire:click="delete" 
                            wire:confirm="{{ __('Are you sure you want to delete this job?') }}"
                            class="block w-full text-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                            @if(in_array($job->status, ['completed', 'in_progress'])) disabled class="bg-gray-400 cursor-not-allowed" @endif>
                        {{ __('Delete Job') }}
                    </button>
                    <a href="{{ route('admin.maintenance-jobs.index') }}" 
                       class="block w-full text-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                        {{ __('Back to List') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    </div>
        </div>
</div>
