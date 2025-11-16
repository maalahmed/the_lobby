<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">{{ __('Maintenance Requests') }}</h1>
        <a href="{{ route('admin.maintenance-requests.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
            {{ __('Create Request') }}
        </a>
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

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <input wire:model.live="search" type="text" placeholder="{{ __('Search requests...') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <select wire:model.live="statusFilter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="pending">{{ __('Pending') }}</option>
                    <option value="approved">{{ __('Approved') }}</option>
                    <option value="assigned">{{ __('Assigned') }}</option>
                    <option value="in_progress">{{ __('In Progress') }}</option>
                    <option value="on_hold">{{ __('On Hold') }}</option>
                    <option value="completed">{{ __('Completed') }}</option>
                    <option value="cancelled">{{ __('Cancelled') }}</option>
                </select>
            </div>
            <div>
                <select wire:model.live="priorityFilter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">{{ __('All Priorities') }}</option>
                    <option value="low">{{ __('Low') }}</option>
                    <option value="normal">{{ __('Normal') }}</option>
                    <option value="high">{{ __('High') }}</option>
                    <option value="urgent">{{ __('Urgent') }}</option>
                </select>
            </div>
            <div>
                <select wire:model.live="categoryFilter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">{{ __('All Categories') }}</option>
                    <option value="plumbing">{{ __('Plumbing') }}</option>
                    <option value="electrical">{{ __('Electrical') }}</option>
                    <option value="hvac">{{ __('HVAC') }}</option>
                    <option value="appliance">{{ __('Appliance') }}</option>
                    <option value="structural">{{ __('Structural') }}</option>
                    <option value="pest_control">{{ __('Pest Control') }}</option>
                    <option value="cleaning">{{ __('Cleaning') }}</option>
                    <option value="landscaping">{{ __('Landscaping') }}</option>
                    <option value="security">{{ __('Security') }}</option>
                    <option value="other">{{ __('Other') }}</option>
                </select>
            </div>
            <div>
                <select wire:model.live="propertyFilter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">{{ __('All Properties') }}</option>
                    @foreach($properties as $property)
                        <option value="{{ $property->id }}">{{ $property->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Request #') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Title') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Property/Unit') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Category') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Priority') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Assigned To') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Scheduled') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($requests as $request)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $request->request_number }}</div>
                            <div class="text-xs text-gray-500">{{ $request->created_at->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $request->title }}</div>
                            @if($request->tenant)
                                <div class="text-xs text-gray-500">{{ $request->tenant->user->name ?? __('N/A') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $request->property->name }}</div>
                            @if($request->unit)
                                <div class="text-xs text-gray-500">{{ __('Unit') }} {{ $request->unit->unit_number }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ __(ucfirst(str_replace('_', ' ', $request->category))) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($request->priority === 'urgent') bg-red-100 text-red-800
                                @elseif($request->priority === 'high') bg-orange-100 text-orange-800
                                @elseif($request->priority === 'normal') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ __(ucfirst($request->priority)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
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
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($request->assignee)
                                <div class="text-sm text-gray-900">{{ $request->assignee->name }}</div>
                            @else
                                <span class="text-sm text-gray-400">{{ __('Unassigned') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($request->scheduled_date)
                                <div class="text-sm text-gray-900">{{ $request->scheduled_date->format('M d, Y') }}</div>
                                @if($request->scheduled_time_start)
                                    <div class="text-xs text-gray-500">{{ $request->scheduled_time_start }} - {{ $request->scheduled_time_end }}</div>
                                @endif
                            @else
                                <span class="text-sm text-gray-400">{{ __('Not scheduled') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.maintenance-requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('View') }}</a>
                            <a href="{{ route('admin.maintenance-requests.edit', $request) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">{{ __('Edit') }}</a>
                            <button wire:click="$dispatch('openModal', { component: 'admin.maintenance-requests.delete-modal', arguments: { request: {{ $request->id }} } })" class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                            {{ __('No maintenance requests found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $requests->links() }}
    </div>
    </div>
        </div>
</div>
