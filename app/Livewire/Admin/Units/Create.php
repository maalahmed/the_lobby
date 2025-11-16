<?php

namespace App\Livewire\Admin\Units;

use App\Models\PropertyUnit;
use App\Models\Property;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

class Create extends Component
{
    use WithFileUploads;

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

    public function save()
    {
        Log::info('Unit Create - Save method called');
        Log::info('Form Data:', [
            'property_id' => $this->property_id,
            'unit_number' => $this->unit_number,
            'floor' => $this->floor,
            'type' => $this->type,
            'area' => $this->area,
            'rent_amount' => $this->rent_amount,
        ]);

        try {
            $this->validate();
            Log::info('Validation passed');

            $unit = PropertyUnit::create([
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

            Log::info('Unit created successfully', ['id' => $unit->id, 'unit_number' => $unit->unit_number]);

            session()->flash('success', __('Unit created successfully'));
            $this->dispatch('unit-created');
            
            Log::info('Redirecting to units index');
            return redirect()->route('admin.units.index');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            session()->flash('error', __('Please fix the validation errors and try again.'));
            throw $e;
        } catch (\Exception $e) {
            Log::error('Unit creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', __('Failed to create unit: ') . $e->getMessage());
        }
    }

    public function render()
    {
        $properties = Property::select('id', 'name')->get();

        /** @var \Illuminate\View\View $view */
        $view = view('livewire.admin.units.create', [
            'properties' => $properties,
        ]);
        
        return $view->layout('layouts.admin');
    }
}
