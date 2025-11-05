<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyAmenity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'name',
        'name_ar',
        'category',
        'description',
        'description_ar',
        'is_available',
        'availability_schedule',
        'booking_required',
        'additional_charge',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'booking_required' => 'boolean',
        'availability_schedule' => 'json',
        'additional_charge' => 'decimal:2',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the property that owns the amenity.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
