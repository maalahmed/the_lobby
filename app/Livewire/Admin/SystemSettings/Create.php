<?php

namespace App\Livewire\Admin\SystemSettings;

use App\Models\SystemSetting;
use Livewire\Component;

class Create extends Component
{
    public $key;
    public $value;
    public $type = 'string';
    public $group;
    public $description;
    public $is_public = false;
    public $is_editable = true;

    protected function rules()
    {
        return [
            'key' => 'required|string|max:100|unique:system_settings,key',
            'value' => 'nullable',
            'type' => 'required|in:string,integer,boolean,json,array',
            'group' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
            'is_editable' => 'boolean',
        ];
    }

    public function save()
    {
        $this->validate();

        SystemSetting::create([
            'key' => $this->key,
            'value' => $this->value ?: null,
            'type' => $this->type,
            'group' => $this->group ?: null,
            'description' => $this->description ?: null,
            'is_public' => $this->is_public,
            'is_editable' => $this->is_editable,
        ]);

        session()->flash('success', 'Setting created successfully.');
        return redirect()->route('admin.system-settings.index');
    }

    public function render()
    {
        return view('livewire.admin.system-settings.create');
    }
}
