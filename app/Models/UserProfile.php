<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

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
        'address',
        'emergency_contact',
        'preferences',
        'documents',
        'avatar_url',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'address' => 'json',
        'emergency_contact' => 'json',
        'preferences' => 'json',
        'documents' => 'json',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
