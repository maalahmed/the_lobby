<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center">
                <a href="{{ route('admin.properties.index') }}" 
                   class="text-gray-600 hover:text-gray-900 {{ app()->getLocale() === 'ar' ? 'ml-4' : 'mr-4' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="{{ app()->getLocale() === 'ar' ? 'M9 5l7 7-7 7' : 'M15 19l-7-7 7-7' }}"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $property->name }}</h1>
                    <p class="mt-1 text-sm text-gray-600">{{ $property->property_code }}</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.properties.edit', $property->id) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                {{ __('Edit') }}
            </a>
            <button wire:click="confirmDelete" 
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                {{ __('Delete') }}
            </button>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="mb-6">
        <span class="px-3 py-1 text-sm font-medium rounded-full
            @if($property->status === 'active') bg-green-100 text-green-800
            @elseif($property->status === 'under_maintenance') bg-yellow-100 text-yellow-800
            @elseif($property->status === 'vacant') bg-blue-100 text-blue-800
            @else bg-gray-100 text-gray-800
            @endif">
            {{ __(ucfirst(str_replace('_', ' ', $property->status))) }}
        </span>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Basic Information') }}</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">{{ __('Property Name') }}</label>
                        <p class="mt-1 text-gray-900">{{ $property->name }}</p>
                        @if($property->name_ar)
                            <p class="mt-1 text-gray-600 text-sm" dir="rtl">{{ $property->name_ar }}</p>
                        @endif
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">{{ __('Property Type') }}</label>
                        <p class="mt-1 text-gray-900">{{ __(ucfirst(str_replace('_', ' ', $property->property_type))) }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">{{ __('Total Units') }}</label>
                        <p class="mt-1 text-gray-900">{{ $property->total_units ?? '-' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">{{ __('Total Floors') }}</label>
                        <p class="mt-1 text-gray-900">{{ $property->total_floors ?? '-' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">{{ __('Year Built') }}</label>
                        <p class="mt-1 text-gray-900">{{ $property->year_built ?? '-' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">{{ __('Parking Spaces') }}</label>
                        <p class="mt-1 text-gray-900">{{ $property->parking_spaces ?? '-' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">{{ __('Total Area') }}</label>
                        <p class="mt-1 text-gray-900">{{ $property->total_area ? number_format($property->total_area, 2) . ' m²' : '-' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">{{ __('Land Area') }}</label>
                        <p class="mt-1 text-gray-900">{{ $property->land_area ? number_format($property->land_area, 2) . ' m²' : '-' }}</p>
                    </div>
                </div>

                @if($property->description)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <label class="text-sm font-medium text-gray-500">{{ __('Description') }}</label>
                        <p class="mt-1 text-gray-900">{{ $property->description }}</p>
                        @if($property->description_ar)
                            <p class="mt-2 text-gray-600 text-sm" dir="rtl">{{ $property->description_ar }}</p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Address Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Address') }}</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-500">{{ __('Address Line 1') }}</label>
                        <p class="mt-1 text-gray-900">{{ $property->address_line_1 }}</p>
                    </div>

                    @if($property->address_line_2)
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-500">{{ __('Address Line 2') }}</label>
                            <p class="mt-1 text-gray-900">{{ $property->address_line_2 }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="text-sm font-medium text-gray-500">{{ __('City') }}</label>
                        <p class="mt-1 text-gray-900">{{ $property->city }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">{{ __('State/Province') }}</label>
                        <p class="mt-1 text-gray-900">{{ $property->state ?? '-' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">{{ __('Postal Code') }}</label>
                        <p class="mt-1 text-gray-900">{{ $property->postal_code ?? '-' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">{{ __('Country') }}</label>
                        <p class="mt-1 text-gray-900">{{ $property->country }}</p>
                    </div>

                    @if($property->latitude && $property->longitude)
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-500">{{ __('Coordinates') }}</label>
                            <p class="mt-1 text-gray-900">{{ $property->latitude }}, {{ $property->longitude }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Financial Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Financial Information') }}</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($property->purchase_price)
                        <div>
                            <label class="text-sm font-medium text-gray-500">{{ __('Purchase Price') }}</label>
                            <p class="mt-1 text-gray-900">{{ number_format($property->purchase_price, 2) }} KWD</p>
                        </div>
                    @endif

                    @if($property->purchase_date)
                        <div>
                            <label class="text-sm font-medium text-gray-500">{{ __('Purchase Date') }}</label>
                            <p class="mt-1 text-gray-900">{{ $property->purchase_date->format('Y-m-d') }}</p>
                        </div>
                    @endif

                    @if($property->current_value)
                        <div>
                            <label class="text-sm font-medium text-gray-500">{{ __('Current Value') }}</label>
                            <p class="mt-1 text-gray-900">{{ number_format($property->current_value, 2) }} KWD</p>
                        </div>
                    @endif

                    @if($property->property_tax_annual)
                        <div>
                            <label class="text-sm font-medium text-gray-500">{{ __('Annual Property Tax') }}</label>
                            <p class="mt-1 text-gray-900">{{ number_format($property->property_tax_annual, 2) }} KWD</p>
                        </div>
                    @endif

                    @if($property->insurance_annual)
                        <div>
                            <label class="text-sm font-medium text-gray-500">{{ __('Annual Insurance') }}</label>
                            <p class="mt-1 text-gray-900">{{ number_format($property->insurance_annual, 2) }} KWD</p>
                        </div>
                    @endif

                    @if($property->insurance_policy_number)
                        <div>
                            <label class="text-sm font-medium text-gray-500">{{ __('Insurance Policy Number') }}</label>
                            <p class="mt-1 text-gray-900">{{ $property->insurance_policy_number }}</p>
                        </div>
                    @endif

                    @if($property->insurance_expiry_date)
                        <div>
                            <label class="text-sm font-medium text-gray-500">{{ __('Insurance Expiry') }}</label>
                            <p class="mt-1 text-gray-900">{{ $property->insurance_expiry_date->format('Y-m-d') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Amenities -->
            @if($property->amenities_list && count($property->amenities_list) > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Amenities') }}</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($property->amenities_list as $amenity)
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">{{ $amenity }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Units -->
            @if($property->units && $property->units->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Units') }} ({{ $property->units->count() }})</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase">{{ __('Unit Number') }}</th>
                                    <th class="px-4 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase">{{ __('Type') }}</th>
                                    <th class="px-4 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                                    <th class="px-4 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase">{{ __('Area') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($property->units as $unit)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $unit->unit_number }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ __(ucfirst($unit->unit_type ?? '-')) }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                @if($unit->status === 'occupied') bg-green-100 text-green-800
                                                @elseif($unit->status === 'vacant') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ __(ucfirst($unit->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $unit->area ? number_format($unit->area, 2) . ' m²' : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Sidebar -->
        <div class="space-y-6">
            <!-- Owner Information -->
            @if($property->owner)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Owner') }}</h2>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-600 font-semibold text-lg">
                                {{ strtoupper(substr($property->owner->first_name, 0, 1)) }}{{ strtoupper(substr($property->owner->last_name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="{{ app()->getLocale() === 'ar' ? 'mr-4' : 'ml-4' }}">
                            <p class="text-sm font-medium text-gray-900">{{ $property->owner->first_name }} {{ $property->owner->last_name }}</p>
                            <p class="text-sm text-gray-500">{{ $property->owner->email }}</p>
                            @if($property->owner->phone_number)
                                <p class="text-sm text-gray-500">{{ $property->owner->phone_number }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Manager Information -->
            @if($property->manager)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Property Manager') }}</h2>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                            <span class="text-green-600 font-semibold text-lg">
                                {{ strtoupper(substr($property->manager->first_name, 0, 1)) }}{{ strtoupper(substr($property->manager->last_name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="{{ app()->getLocale() === 'ar' ? 'mr-4' : 'ml-4' }}">
                            <p class="text-sm font-medium text-gray-900">{{ $property->manager->first_name }} {{ $property->manager->last_name }}</p>
                            <p class="text-sm text-gray-500">{{ $property->manager->email }}</p>
                            @if($property->management_start_date)
                                <p class="text-xs text-gray-500 mt-1">{{ __('Since') }}: {{ $property->management_start_date->format('Y-m-d') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Quick Stats') }}</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ __('Total Units') }}</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $property->units->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ __('Occupied Units') }}</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $property->units->where('status', 'occupied')->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ __('Vacant Units') }}</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $property->units->where('status', 'vacant')->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                        <span class="text-sm text-gray-600">{{ __('Occupancy Rate') }}</span>
                        <span class="text-sm font-semibold text-gray-900">
                            {{ $property->units->count() > 0 ? round(($property->units->where('status', 'occupied')->count() / $property->units->count()) * 100, 1) : 0 }}%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($property->notes)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Notes') }}</h2>
                    <p class="text-sm text-gray-700">{{ $property->notes }}</p>
                </div>
            @endif

            <!-- Metadata -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Metadata') }}</h2>
                <div class="space-y-2 text-sm">
                    <div>
                        <span class="text-gray-500">{{ __('Created') }}:</span>
                        <span class="text-gray-900">{{ $property->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">{{ __('Last Updated') }}:</span>
                        <span class="text-gray-900">{{ $property->updated_at->format('Y-m-d H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-data @click="$wire.showDeleteModal = false">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" @click.stop>
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">{{ __('Delete Property') }}</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            {{ __('Are you sure you want to delete this property? This action cannot be undone.') }}
                        </p>
                    </div>
                    <div class="flex gap-4 justify-center mt-4">
                        <button @click="$wire.showDeleteModal = false" 
                                class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            {{ __('Cancel') }}
                        </button>
                        <button wire:click="deleteProperty" 
                                class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            {{ __('Delete') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="fixed bottom-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="fixed bottom-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
</div>
