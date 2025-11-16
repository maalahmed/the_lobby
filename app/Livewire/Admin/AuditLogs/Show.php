<?php

namespace App\Livewire\Admin\AuditLogs;

use App\Models\AuditLog;
use Livewire\Component;

class Show extends Component
{
    public AuditLog $log;

    public function mount($log)
    {
        if ($log instanceof AuditLog) {
            $this->log = $log;
        } else {
            $this->log = AuditLog::with(['user'])->findOrFail($log);
        }
    }

    public function render()
    {
        return view('livewire.admin.audit-logs.show');
    }
}
