<?php

namespace App\Livewire\Admin\Properties;

use App\Models\Property;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

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

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'landlord_id' => 'required|exists:users,id',
            'type' => 'required|in:residential,commercial,mixed,industrial,villa,building,apartment',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
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

    public function save()
    {
        $this->validate();

        $property = Property::create([
            'name' => $this->name,
            'owner_id' => $this->landlord_id,
            'property_type' => $this->type,
            'address_line_1' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'description' => $this->description,
            'total_units' => $this->total_units,
            'year_built' => $this->year_built,
            'status' => $this->status,
        ]);

        // Handle image uploads if Spatie Media Library is configured
        // if ($this->images) {
        //     foreach ($this->images as $image) {
        //         $property->addMedia($image->getRealPath())
        //             ->toMediaCollection('images');
        //     }
        // }

        session()->flash('success', __('Property created successfully'));
        
        return redirect()->route('admin.properties.index');
    }

    public function render()
    {
        $landlords = User::role('Landlord')->get();

        /** @var \Illuminate\View\View $view */
        $view = view('livewire.admin.properties.create', [
            'landlords' => $landlords,
        ]);
        
        return $view->layout('layouts.admin', ['title' => __('Create Property')]);
    }
}
