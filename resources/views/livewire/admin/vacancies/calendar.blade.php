<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="~bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 ~text-gray-900">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('admin.vacancies.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </a>
                        <h2 class="text-2xl font-semibold">{{ __('Lease Calendar') }}</h2>
                    </div>
                    <div class="flex items-center gap-4">
                        <!-- Property Filter -->
                        <select wire:model.live="selectedProperty" class="~bg-gray-50 border ~border-gray-300 ~text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                            <option value="">{{ __('All Properties') }}</option>
                            @foreach($properties as $property)
                                <option value="{{ $property->id }}">{{ $property->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Calendar Navigation -->
                <div class="flex items-center justify-between mb-6">
                    <button wire:click="previousMonth" class="px-4 py-2 ~bg-gray-200 ~text-gray-700 rounded-lg hover:~bg-gray-300">
                        ← {{ __('Previous') }}
                    </button>
                    
                    <div class="flex items-center gap-4">
                        <h3 class="text-xl font-semibold">{{ $calendarData['monthName'] }}</h3>
                        <button wire:click="today" class="px-3 py-1 text-sm ~bg-blue-100 ~text-blue-700 rounded-lg hover:~bg-blue-200">
                            {{ __('Today') }}
                        </button>
                    </div>
                    
                    <button wire:click="nextMonth" class="px-4 py-2 ~bg-gray-200 ~text-gray-700 rounded-lg hover:~bg-gray-300">
                        {{ __('Next') }} →
                    </button>
                </div>

                <!-- Stats -->
                <div class="mb-6 p-4 ~bg-blue-50 rounded-lg">
                    <p class="text-sm ~text-gray-700">
                        {{ __('Total Active Leases') }}: <span class="font-semibold">{{ $calendarData['totalLeases'] }}</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="~bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- Weekday Headers -->
                <div class="grid grid-cols-7 gap-px mb-2">
                    @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <div class="text-center font-semibold ~text-gray-700 py-2">
                            {{ __($day) }}
                        </div>
                    @endforeach
                </div>

                <!-- Calendar Days -->
                <div class="grid grid-cols-7 gap-px ~bg-gray-200 border ~border-gray-200">
                    @foreach($calendarData['weeks'] as $week)
                        @foreach($week as $day)
                            <div class="
                                min-h-[120px] ~bg-white p-2
                                {{ !$day['isCurrentMonth'] ? '~bg-gray-50 opacity-50' : '' }}
                                {{ $day['isToday'] ? 'ring-2 ring-blue-500' : '' }}
                            ">
                                <!-- Date Number -->
                                <div class="flex items-start justify-between mb-1">
                                    <span class="
                                        text-sm font-semibold
                                        {{ $day['isToday'] ? '~bg-blue-500 ~text-white rounded-full w-6 h-6 flex items-center justify-center' : '~text-gray-700' }}
                                    ">
                                        {{ $day['date']->format('j') }}
                                    </span>
                                    @if($day['eventCount'] > 0)
                                        <span class="text-xs ~bg-purple-100 ~text-purple-700 px-2 py-0.5 rounded-full">
                                            {{ $day['eventCount'] }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Lease Events -->
                                <div class="space-y-1">
                                    @php
                                        $displayLimit = 3;
                                        $displayedLeases = $day['leases']->take($displayLimit);
                                        $remainingCount = $day['eventCount'] - $displayLimit;
                                    @endphp
                                    
                                    @foreach($displayedLeases as $lease)
                                        @php
                                            $isStart = $lease->start_date->isSameDay($day['date']);
                                            $isEnd = $lease->end_date->isSameDay($day['date']);
                                            $statusColor = match($lease->status) {
                                                'active' => 'bg-green-500',
                                                'pending' => 'bg-yellow-500',
                                                'expired' => 'bg-red-500',
                                                default => 'bg-gray-500'
                                            };
                                        @endphp
                                        
                                        <div 
                                            class="text-xs px-2 py-1 rounded {{ $statusColor }} text-white truncate cursor-pointer hover:opacity-80"
                                            title="{{ $lease->unit->property->name }} - Unit {{ $lease->unit->unit_number }} - {{ $lease->tenant->user->name }}"
                                        >
                                            @if($isStart)
                                                <span class="font-bold">▶</span>
                                            @endif
                                            {{ Str::limit($lease->unit->property->name, 15) }}
                                            @if($isEnd)
                                                <span class="font-bold">◀</span>
                                            @endif
                                        </div>
                                    @endforeach

                                    @if($remainingCount > 0)
                                        <div class="text-xs ~text-gray-500 pl-2">
                                            +{{ $remainingCount }} {{ __('more') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>

                <!-- Legend -->
                <div class="mt-6 flex items-center gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-green-500 rounded"></div>
                        <span>{{ __('Active') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-yellow-500 rounded"></div>
                        <span>{{ __('Pending') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-red-500 rounded"></div>
                        <span>{{ __('Expired') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold">▶</span>
                        <span>{{ __('Lease Start') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold">◀</span>
                        <span>{{ __('Lease End') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
