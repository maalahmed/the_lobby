<?php

namespace App\Livewire\Admin\AuditLogs;

use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $eventFilter = '';
    public $auditableTypeFilter = '';

    protected $queryString = ['search', 'eventFilter', 'auditableTypeFilter'];

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

    public function render()
    {
        $logs = AuditLog::query()
            ->with(['user'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('ip_address', 'like', '%' . $this->search . '%')
                      ->orWhere('user_agent', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->eventFilter, function ($query) {
                $query->where('event', $this->eventFilter);
            })
            ->when($this->auditableTypeFilter, function ($query) {
                $query->where('auditable_type', $this->auditableTypeFilter);
            })
            ->latest()
            ->paginate(15);

        $events = AuditLog::distinct()->pluck('event')->sort();
        $auditableTypes = AuditLog::distinct()->pluck('auditable_type')->filter()->sort();

        return view('livewire.admin.audit-logs.index', [
            'logs' => $logs,
            'events' => $events,
            'auditableTypes' => $auditableTypes,
        ])->layout('layouts.admin');
    }
}
