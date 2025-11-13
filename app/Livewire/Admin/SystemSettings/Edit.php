<?php

namespace App\Livewire\Admin\SystemSettings;

use App\Models\SystemSetting;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Edit extends Component
{
    public SystemSetting $setting;
    
    public $key;
    public $value;
    public $type;
    public $group;
    public $description;
    public $is_public;
    public $is_editable;

    public function mount($setting)
    {
        Log::info('SystemSettings Edit mount called', [
            'setting_param' => $setting,
            'setting_type' => gettype($setting),
            'is_model' => $setting instanceof SystemSetting
        ]);
        
        if ($setting instanceof SystemSetting) {
            $this->setting = $setting;
        } else {
            $this->setting = SystemSetting::findOrFail($setting);
        }
        
        $this->key = $this->setting->key;
        $this->value = $this->setting->value;
        $this->type = $this->setting->type;
        $this->group = $this->setting->group;
        $this->description = $this->setting->description;
        $this->is_public = $this->setting->is_public;
        $this->is_editable = $this->setting->is_editable;
        
        Log::info('SystemSettings Edit mount completed', ['setting_id' => $this->setting->id]);
    }

    protected function rules()
    {
        return [
            'key' => 'required|string|max:100|unique:system_settings,key,' . $this->setting->id,
            'value' => 'nullable',
            'type' => 'required|in:string,integer,boolean,json,array',
            'group' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
            'is_editable' => 'boolean',
        ];
    }

    public function update()
    {
        $this->validate();

        Log::info('SystemSettings Edit update called', [
            'setting_id' => $this->setting->id,
            'key' => $this->key,
            'value' => $this->value,
            'type' => $this->type,
            'group' => $this->group,
            'description' => $this->description,
            'is_public' => $this->is_public,
            'is_editable' => $this->is_editable,
        ]);

        $this->setting->update([
            'key' => $this->key,
            'value' => $this->value ?: null,
            'type' => $this->type,
            'group' => $this->group ?: null,
            'description' => $this->description ?: null,
            'is_public' => $this->is_public,
            'is_editable' => $this->is_editable,
        ]);

        Log::info('SystemSettings Edit update completed', ['setting_id' => $this->setting->id]);

        session()->flash('success', 'Setting updated successfully.');
        return redirect()->route('admin.system-settings.index');
    }

    public function render()
    {
        return view('livewire.admin.system-settings.edit')->layout('layouts.admin');
    }
}
