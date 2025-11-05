<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'profile_type',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'nationality',
        'national_id',
        'passport_number',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'preferred_language',
        'preferred_currency',
        'timezone',
        'notification_preferences',
        'avatar_url',
        'id_document_url',
        'additional_documents',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'notification_preferences' => 'json',
        'additional_documents' => 'json',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
