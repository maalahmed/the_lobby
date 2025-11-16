<?php

namespace App\Livewire\Admin\Vacancies;

use Livewire\Component;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\LeaseContract;
use Carbon\Carbon;

class Calendar extends Component
{
    public $selectedProperty = '';
    public $currentMonth;
    public $currentYear;

    protected $queryString = ['selectedProperty', 'currentMonth', 'currentYear'];

    public function mount()
    {
        $this->currentMonth = $this->currentMonth ?? now()->month;
        $this->currentYear = $this->currentYear ?? now()->year;
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
    }

    public function today()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
    }

    public function getCalendarData()
    {
        $startOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfDay();
        $endOfMonth = $startOfMonth->copy()->endOfMonth()->endOfDay();

        // Get start of calendar (previous month days if needed)
        $startOfCalendar = $startOfMonth->copy()->startOfWeek();
        $endOfCalendar = $endOfMonth->copy()->endOfWeek();

        $query = LeaseContract::with(['unit.property', 'tenant'])
            ->where(function ($q) use ($startOfCalendar, $endOfCalendar) {
                $q->whereBetween('start_date', [$startOfCalendar, $endOfCalendar])
                  ->orWhereBetween('end_date', [$startOfCalendar, $endOfCalendar])
                  ->orWhere(function ($q2) use ($startOfCalendar, $endOfCalendar) {
                      $q2->where('start_date', '<=', $startOfCalendar)
                         ->where('end_date', '>=', $endOfCalendar);
                  });
            });

        if ($this->selectedProperty) {
            $query->where('property_id', $this->selectedProperty);
        }

        $leases = $query->get();

        // Build calendar grid
        $weeks = [];
        $currentDate = $startOfCalendar->copy();

        while ($currentDate <= $endOfCalendar) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $dayLeases = $leases->filter(function ($lease) use ($currentDate) {
                    return $currentDate->between($lease->start_date, $lease->end_date);
                });

                $week[] = [
                    'date' => $currentDate->copy(),
                    'isCurrentMonth' => $currentDate->month == $this->currentMonth,
                    'isToday' => $currentDate->isToday(),
                    'leases' => $dayLeases,
                    'eventCount' => $dayLeases->count(),
                ];

                $currentDate->addDay();
            }
            $weeks[] = $week;
        }

        return [
            'weeks' => $weeks,
            'monthName' => $startOfMonth->format('F Y'),
            'totalLeases' => $leases->count(),
        ];
    }

    public function render()
    {
        $properties = Property::orderBy('name')->get();
        $calendarData = $this->getCalendarData();

        return view('livewire.admin.vacancies.calendar', [
            'properties' => $properties,
            'calendarData' => $calendarData,
        ]);
    }
}
