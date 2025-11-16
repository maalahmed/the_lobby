<?php

namespace App\Livewire\Admin\Units;

use App\Models\PropertyUnit;
use App\Models\Property;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public PropertyUnit $unit;

    // Unit fields matching migration exactly
    public $property_id = '';
    public $unit_number = '';
    public $floor = '';
    public $type = 'studio';
    public $area = '';
    public $bedrooms = 0;
    public $bathrooms = 1;
    public $balconies = 0;
    public $parking_spaces = 0;
    public $rent_amount = '';
    public $security_deposit = '';
    public $rent_frequency = 'monthly';
    public $furnished = 'unfurnished';
    public $status = 'available';
    public $available_from = '';
    public $images = [];

    public function mount(PropertyUnit $unit)
    {
        $this->unit = $unit;
        
        // Populate form fields - using exact column names from migration
        $this->property_id = $this->unit->property_id;
        $this->unit_number = $this->unit->unit_number;
        $this->floor = $this->unit->floor;
        $this->type = $this->unit->type;
        $this->area = $this->unit->area;
        $this->bedrooms = $this->unit->bedrooms;
        $this->bathrooms = $this->unit->bathrooms;
        $this->balconies = $this->unit->balconies;
        $this->parking_spaces = $this->unit->parking_spaces;
        $this->rent_amount = $this->unit->rent_amount;
        $this->security_deposit = $this->unit->security_deposit;
        $this->rent_frequency = $this->unit->rent_frequency;
        $this->furnished = $this->unit->furnished;
        $this->status = $this->unit->status;
        $this->available_from = $this->unit->available_from?->format('Y-m-d');
    }

    protected function rules()
    {
        return [
            'property_id' => 'required|exists:properties,id',
            'unit_number' => 'required|string|max:20',
            'floor' => 'nullable|integer',
            'type' => 'required|in:studio,1br,2br,3br,4br,5br+,penthouse,office,retail,warehouse',
            'area' => 'required|numeric|min:1',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:1',
            'balconies' => 'nullable|integer|min:0',
            'parking_spaces' => 'nullable|integer|min:0',
            'rent_amount' => 'required|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'rent_frequency' => 'required|in:monthly,quarterly,semi_annual,annual',
            'furnished' => 'required|in:unfurnished,semi_furnished,fully_furnished',
            'status' => 'required|in:available,occupied,maintenance,reserved',
            'available_from' => 'nullable|date',
            'images.*' => 'nullable|image|max:2048',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'property_id' => __('property'),
            'unit_number' => __('unit number'),
            'floor' => __('floor'),
            'type' => __('unit type'),
            'area' => __('area'),
            'bedrooms' => __('bedrooms'),
            'bathrooms' => __('bathrooms'),
            'balconies' => __('balconies'),
            'parking_spaces' => __('parking spaces'),
            'rent_amount' => __('rent amount'),
            'security_deposit' => __('security deposit'),
            'rent_frequency' => __('rent frequency'),
            'furnished' => __('furnished status'),
            'status' => __('status'),
            'available_from' => __('available from'),
        ];
    }

    public function update()
    {
        $this->validate();

        $this->unit->update([
            'property_id' => $this->property_id,
            'unit_number' => $this->unit_number,
            'floor' => $this->floor ?: null,
            'type' => $this->type,
            'area' => $this->area,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'balconies' => $this->balconies ?: null,
            'parking_spaces' => $this->parking_spaces ?: null,
            'rent_amount' => $this->rent_amount,
            'security_deposit' => $this->security_deposit ?: null,
            'rent_frequency' => $this->rent_frequency,
            'furnished' => $this->furnished,
            'status' => $this->status,
            'available_from' => $this->available_from ?: null,
        ]);

        session()->flash('success', __('Unit updated successfully'));
        
        return redirect()->route('admin.units.show', $this->unit->id);
    }

    public function render()
    {
        $properties = Property::select('id', 'name')->get();

        /** @var \Illuminate\View\View $view */
        $view = view('livewire.admin.units.edit', [
            'properties' => $properties,
        ]);
        
        return $view->layout('layouts.admin', ['title' => __('Edit Unit')]);
    }
}
