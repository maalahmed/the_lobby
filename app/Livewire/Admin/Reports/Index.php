<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\PropertyUnit;
use App\Models\LeaseContract;
use App\Models\MaintenanceJob;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $dateRange = 'month';
    public $startDate;
    public $endDate;
    public $selectedProperty = '';

    public function mount()
    {
        $this->setDateRange();
    }

    public function updatedDateRange()
    {
        $this->setDateRange();
    }

    private function setDateRange()
    {
        switch ($this->dateRange) {
            case 'today':
                $this->startDate = Carbon::today()->format('Y-m-d');
                $this->endDate = Carbon::today()->format('Y-m-d');
                break;
            case 'week':
                $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
                break;
            case 'month':
                $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            case 'quarter':
                $this->startDate = Carbon::now()->startOfQuarter()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfQuarter()->format('Y-m-d');
                break;
            case 'year':
                $this->startDate = Carbon::now()->startOfYear()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfYear()->format('Y-m-d');
                break;
            case 'custom':
                // Keep current dates
                break;
        }
    }

    public function render()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        // Revenue Statistics
        $revenue = [
            'total' => Payment::where('status', 'completed')
                ->whereBetween('created_at', [$start, $end])
                ->sum('amount'),
            'rental' => Payment::where('status', 'completed')
                ->whereHas('invoice', function($q) {
                    $q->where('type', 'rent');
                })
                ->whereBetween('created_at', [$start, $end])
                ->sum('amount'),
            'maintenance' => Payment::where('status', 'completed')
                ->whereHas('invoice', function($q) {
                    $q->where('type', 'maintenance');
                })
                ->whereBetween('created_at', [$start, $end])
                ->sum('amount'),
            'other' => Payment::where('status', 'completed')
                ->whereHas('invoice', function($q) {
                    $q->whereNotIn('type', ['rent', 'maintenance']);
                })
                ->whereBetween('created_at', [$start, $end])
                ->sum('amount'),
        ];

        // Expenses
        $expenses = [
            'maintenance' => MaintenanceJob::whereBetween('completed_at', [$start, $end])
                ->sum('final_amount'),
            'total' => MaintenanceJob::whereBetween('completed_at', [$start, $end])
                ->sum('final_amount'),
        ];

        // Occupancy
        $occupancy = [
            'total_units' => PropertyUnit::count(),
            'occupied' => PropertyUnit::where('status', 'occupied')->count(),
            'available' => PropertyUnit::where('status', 'available')->count(),
            'maintenance' => PropertyUnit::where('status', 'maintenance')->count(),
            'rate' => PropertyUnit::count() > 0
                ? round((PropertyUnit::where('status', 'occupied')->count() / PropertyUnit::count()) * 100, 1)
                : 0,
        ];

        // Collection Rate
        $totalInvoiced = Invoice::whereBetween('invoice_date', [$start, $end])
            ->sum('total_amount');
        $totalCollected = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');
        $collectionRate = $totalInvoiced > 0
            ? round(($totalCollected / $totalInvoiced) * 100, 1)
            : 0;

        // Monthly trends (last 6 months)
        $monthlyRevenue = [];
        $monthlyExpenses = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthlyRevenue[] = [
                'month' => $month->format('M'),
                'amount' => Payment::where('status', 'completed')
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->sum('amount'),
            ];

            $monthlyExpenses[] = [
                'month' => $month->format('M'),
                'amount' => MaintenanceJob::whereBetween('completed_at', [$monthStart, $monthEnd])
                    ->sum('final_amount'),
            ];
        }

        // Top Properties by Revenue
        $topProperties = DB::table('payments')
            ->join('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->join('lease_contracts', 'invoices.contract_id', '=', 'lease_contracts.id')
            ->join('property_units', 'lease_contracts.unit_id', '=', 'property_units.id')
            ->join('properties', 'property_units.property_id', '=', 'properties.id')
            ->where('payments.status', 'completed')
            ->whereBetween('payments.created_at', [$start, $end])
            ->select('properties.name', DB::raw('SUM(payments.amount) as total_revenue'))
            ->groupBy('properties.id', 'properties.name')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        return view('livewire.admin.reports.index', [
            'revenue' => $revenue,
            'expenses' => $expenses,
            'occupancy' => $occupancy,
            'collectionRate' => $collectionRate,
            'monthlyRevenue' => $monthlyRevenue,
            'monthlyExpenses' => $monthlyExpenses,
            'topProperties' => $topProperties,
            'netIncome' => $revenue['total'] - $expenses['total'],
        ]);
    }
}
