<?php

namespace App\Livewire\Admin\Properties;

use App\Models\Property;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Property $property;

    // Property fields
    public $name = '';
    public $landlord_id = '';
    public $type = 'residential';
    public $address = '';
    public $city = '';
    public $state = '';
    public $postal_code = '';
    public $country = 'Kuwait';
    public $description = '';
    public $total_units = 0;
    public $year_built = '';
    public $status = 'active';
    public $images = [];

    public function mount($property)
    {
        $this->property = Property::findOrFail($property);
        
        // Populate form fields
        $this->name = $this->property->name;
        $this->landlord_id = $this->property->owner_id;
        $this->type = $this->property->type;  // Fixed: column is 'type' not 'property_type'
        $this->address = $this->property->address_line_1;
        $this->city = $this->property->city;
        $this->state = $this->property->state;
        $this->postal_code = $this->property->postal_code;
        $this->country = $this->property->country;
        $this->description = $this->property->description;
        $this->total_units = $this->property->total_units;
        $this->year_built = $this->property->built_year;  // Fixed: column is 'built_year' not 'year_built'
        $this->status = $this->property->status;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'landlord_id' => 'required|exists:users,id',
            'type' => 'required|in:residential,commercial,mixed,industrial,villa,building,apartment',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',  // Fixed: required per migration
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'description' => 'nullable|string',
            'total_units' => 'required|integer|min:1',
            'year_built' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'status' => 'required|in:active,inactive,under_maintenance,vacant',
            'images.*' => 'nullable|image|max:2048',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'name' => __('property name'),
            'landlord_id' => __('landlord'),
            'type' => __('property type'),
            'address' => __('address'),
            'city' => __('city'),
            'state' => __('state'),
            'postal_code' => __('postal code'),
            'country' => __('country'),
            'description' => __('description'),
            'total_units' => __('total units'),
            'year_built' => __('year built'),
            'status' => __('status'),
        ];
    }

    public function update()
    {
        $this->validate();

        $this->property->update([
            'name' => $this->name,
            'owner_id' => $this->landlord_id,
            'type' => $this->type,  // Fixed: column is 'type' not 'property_type'
            'address_line_1' => $this->address,
            'city' => $this->city,
            'state' => $this->state,  // Required field
            'postal_code' => $this->postal_code ?: null,  // Nullable
            'country' => $this->country,
            'description' => $this->description ?: null,  // Nullable
            'total_units' => $this->total_units,
            'built_year' => $this->year_built ?: null,  // Fixed: column is 'built_year' with null conversion
            'status' => $this->status,
        ]);

        // Handle image uploads if Spatie Media Library is configured
        // if ($this->images) {
        //     foreach ($this->images as $image) {
        //         $this->property->addMedia($image->getRealPath())
        //             ->toMediaCollection('images');
        //     }
        // }

        session()->flash('success', __('Property updated successfully'));
        
        return redirect()->route('admin.properties.show', $this->property->id);
    }

    public function render()
    {
        $landlords = User::role('Landlord')->get();

        /** @var \Illuminate\View\View $view */
        $view = view('livewire.admin.properties.edit', [
            'landlords' => $landlords,
        ]);
        
        return $view->layout('layouts.admin', ['title' => __('Edit Property')]);
    }
}
