<?php

namespace App\Livewire\Admin\AuditLogs;

use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $eventFilter = '';
    public $auditableTypeFilter = '';
    public $dateFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'eventFilter' => ['except' => ''],
        'auditableTypeFilter' => ['except' => ''],
        'dateFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingEventFilter()
    {
        $this->resetPage();
    }

    public function updatingAuditableTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = AuditLog::query()->with(['user']);

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('ip_address', 'like', '%' . $this->search . '%')
                  ->orWhere('user_agent', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filter by event
        if ($this->eventFilter) {
            $query->where('event', $this->eventFilter);
        }

        // Filter by model type
        if ($this->auditableTypeFilter) {
            $query->where('auditable_type', $this->auditableTypeFilter);
        }

        // Filter by date range
        if ($this->dateFilter) {
            $query->where(function ($q) {
                switch ($this->dateFilter) {
                    case 'today':
                        $q->whereDate('created_at', Carbon::today());
                        break;
                    case 'yesterday':
                        $q->whereDate('created_at', Carbon::yesterday());
                        break;
                    case 'week':
                        $q->where('created_at', '>=', Carbon::now()->subDays(7));
                        break;
                    case 'month':
                        $q->where('created_at', '>=', Carbon::now()->subDays(30));
                        break;
                }
            });
        }

        $logs = $query->latest()->paginate(15);

        // Calculate stats
        $stats = [
            'total' => AuditLog::count(),
            'created' => AuditLog::where('event', 'created')->count(),
            'updated' => AuditLog::where('event', 'updated')->count(),
            'deleted' => AuditLog::where('event', 'deleted')->count(),
        ];

        $events = AuditLog::distinct()->pluck('event')->filter()->sort();
        $auditableTypes = AuditLog::distinct()->pluck('auditable_type')->filter()->sort();

        return view('livewire.admin.audit-logs.index', [
            'logs' => $logs,
            'stats' => $stats,
            'events' => $events,
            'auditableTypes' => $auditableTypes,
        ]);
    }
}
