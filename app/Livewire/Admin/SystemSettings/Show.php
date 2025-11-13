<?php

namespace App\Livewire\Admin\SystemSettings;

use App\Models\SystemSetting;
use Livewire\Component;

class Show extends Component
{
    public SystemSetting $setting;

    public function mount($setting)
    {
        $this->setting = SystemSetting::findOrFail($setting);
    }

    public function delete()
    {
        if (!$this->setting->is_editable) {
            session()->flash('error', 'This setting cannot be deleted.');
            return;
        }

        $this->setting->delete();
        session()->flash('success', 'Setting deleted successfully.');
        return redirect()->route('admin.system-settings.index');
    }

    public function render()
    {
        return view('livewire.admin.system-settings.show')->layout('layouts.admin');
    }
}
