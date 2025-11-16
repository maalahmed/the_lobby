<?php

namespace App\Livewire\Admin\SystemSettings;

use App\Models\SystemSetting;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Show extends Component
{
    public SystemSetting $setting;

    public function mount($setting)
    {
        Log::info('SystemSettings Show mount called', [
            'setting_param' => $setting,
            'setting_type' => gettype($setting),
            'is_model' => $setting instanceof SystemSetting
        ]);

        if ($setting instanceof SystemSetting) {
            $this->setting = $setting;
        } else {
            $this->setting = SystemSetting::findOrFail($setting);
        }

        Log::info('SystemSettings Show mount completed', ['setting_id' => $this->setting->id]);
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
        return view('livewire.admin.system-settings.show');
    }
}
