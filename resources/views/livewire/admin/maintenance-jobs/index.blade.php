<div>
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Maintenance Jobs') }}</h1>
            <p class="mt-1 text-sm text-gray-600">{{ __('Manage service provider jobs for maintenance requests') }}</p>
        </div>
        <a href="{{ route('admin.maintenance-jobs.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
            {{ __('Create Job') }}
        </a>
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

    <!-- Filters -->
    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Search') }}</label>
                <input type="text" wire:model.live="search" 
                       placeholder="{{ __('Job #, Description, Provider...') }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                <select wire:model.live="statusFilter" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="assigned">{{ __('Assigned') }}</option>
                    <option value="accepted">{{ __('Accepted') }}</option>
                    <option value="rejected">{{ __('Rejected') }}</option>
                    <option value="in_progress">{{ __('In Progress') }}</option>
                    <option value="completed">{{ __('Completed') }}</option>
                    <option value="cancelled">{{ __('Cancelled') }}</option>
                </select>
            </div>

            <!-- Payment Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Payment Status') }}</label>
                <select wire:model.live="paymentStatusFilter" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">{{ __('All Payment Statuses') }}</option>
                    <option value="pending">{{ __('Pending') }}</option>
                    <option value="approved">{{ __('Approved') }}</option>
                    <option value="paid">{{ __('Paid') }}</option>
                </select>
            </div>

            <!-- Service Provider Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Service Provider') }}</label>
                <select wire:model.live="serviceProviderFilter" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">{{ __('All Providers') }}</option>
                    @foreach($serviceProviders as $provider)
                        <option value="{{ $provider->id }}">{{ $provider->company_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Maintenance Request Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Request') }}</label>
                <select wire:model.live="maintenanceRequestFilter" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">{{ __('All Requests') }}</option>
                    @foreach($maintenanceRequests as $request)
                        <option value="{{ $request->id }}">{{ $request->request_number }} - {{ $request->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Jobs Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Job #') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Request') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Service Provider') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Scheduled') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Payment') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Amount') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($jobs as $job)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $job->job_number }}</div>
                                <div class="text-xs text-gray-500">{{ $job->created_at->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $job->maintenanceRequest->request_number }}</div>
                                <div class="text-xs text-gray-500">{{ $job->maintenanceRequest->title }}</div>
                                <div class="text-xs text-gray-500">{{ $job->maintenanceRequest->property->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $job->serviceProvider->company_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $job->scheduled_date->format('M d, Y') }}</div>
                                @if($job->scheduled_time_start)
                                    <div class="text-xs text-gray-500">{{ $job->scheduled_time_start->format('H:i') }} - {{ $job->scheduled_time_end?->format('H:i') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
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
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($job->payment_status === 'paid') bg-green-100 text-green-800
                                    @elseif($job->payment_status === 'approved') bg-blue-100 text-blue-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ __(ucfirst($job->payment_status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($job->final_amount)
                                    <div class="text-sm font-medium text-gray-900">{{ number_format((float)$job->final_amount, 2) }} SAR</div>
                                    <div class="text-xs text-gray-500">{{ __('Final') }}</div>
                                @elseif($job->quoted_amount)
                                    <div class="text-sm text-gray-900">{{ number_format((float)$job->quoted_amount, 2) }} SAR</div>
                                    <div class="text-xs text-gray-500">{{ __('Quoted') }}</div>
                                @else
                                    <span class="text-sm text-gray-400">{{ __('N/A') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.maintenance-jobs.show', $job) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('View') }}</a>
                                <a href="{{ route('admin.maintenance-jobs.edit', $job) }}" class="text-green-600 hover:text-green-900 mr-3">{{ __('Edit') }}</a>
                                <button wire:click="delete({{ $job->id }})" 
                                        wire:confirm="{{ __('Are you sure you want to delete this job?') }}"
                                        class="text-red-600 hover:text-red-900"
                                        @if(in_array($job->status, ['completed', 'in_progress'])) disabled class="text-gray-400 cursor-not-allowed" @endif>
                                    {{ __('Delete') }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                {{ __('No maintenance jobs found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $jobs->links() }}
    </div>
</div>
