<?php

namespace App\Livewire\Admin;

use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\LeaseContract;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\MaintenanceRequest;
use App\Models\Tenant;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_properties' => Property::count(),
            'total_units' => PropertyUnit::count(),
            'occupied_units' => PropertyUnit::where('status', 'occupied')->count(),
            'available_units' => PropertyUnit::where('status', 'available')->count(),
            'active_contracts' => LeaseContract::where('status', 'active')->count(),
            'total_tenants' => Tenant::count(),
            'total_landlords' => 0, // TODO: Implement landlords when role system is added
            'pending_invoices' => Invoice::whereIn('status', ['draft', 'sent'])->count(),
            'overdue_invoices' => Invoice::where('status', 'overdue')->count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'pending_maintenance' => MaintenanceRequest::whereIn('status', ['pending', 'in_progress'])->count(),
        ];
        
        // Recent activities
        $recentContracts = LeaseContract::with(['tenant', 'unit.property'])
            ->latest()
            ->take(5)
            ->get();
            
        $recentPayments = Payment::with(['invoice.contract.tenant'])
            ->latest()
            ->take(5)
            ->get();
            
        $recentMaintenance = MaintenanceRequest::with(['unit.property', 'tenant'])
            ->latest()
            ->take(5)
            ->get();
        
        /** @var \Illuminate\View\View $view */
        $view = view('livewire.admin.dashboard', compact('stats', 'recentContracts', 'recentPayments', 'recentMaintenance'));
        
        return $view]);
    }
}
