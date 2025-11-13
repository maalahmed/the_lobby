<?php

namespace App\Livewire\Admin\SystemSettings;

use App\Models\SystemSetting;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $groupFilter = '';

    protected $queryString = ['search', 'groupFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingGroupFilter()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $setting = SystemSetting::findOrFail($id);
        
        if (!$setting->is_editable) {
            session()->flash('error', 'This setting cannot be deleted.');
            return;
        }

        $setting->delete();
        session()->flash('success', 'Setting deleted successfully.');
    }

    public function render()
    {
        $settings = SystemSetting::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('key', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('value', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->groupFilter, function ($query) {
                $query->where('group', $this->groupFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.system-settings.index', [
            'settings' => $settings,
        ])->layout('layouts.admin');
    }
}
