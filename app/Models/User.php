<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'phone',
        'phone_verified_at',
        'two_factor_enabled',
        'two_factor_secret',
        'user_type',
        'status',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_enabled' => 'boolean',
        'last_login_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($user) {
            if (empty($user->uuid)) {
                $user->uuid = \Illuminate\Support\Str::uuid();
            }
        });
    }

    /**
     * Get the user's profile.
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Get properties owned by the user (landlord).
     */
    public function properties()
    {
        return $this->hasMany(Property::class, 'owner_id');
    }

    /**
     * Get the tenant record if user is a tenant.
     */
    public function tenant()
    {
        return $this->hasOne(Tenant::class);
    }

    /**
     * Get the service provider record if user is a service provider.
     */
    public function serviceProvider()
    {
        return $this->hasOne(ServiceProvider::class);
    }

    /**
     * Get messages sent by this user.
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get messages received by this user.
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    /**
     * Get notifications for this user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get audit logs for this user.
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Scope: Filter by user type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('user_type', $type);
    }

    /**
     * Scope: Tenants only
     */
    public function scopeTenants($query)
    {
        return $query->where('user_type', 'tenant');
    }

    /**
     * Scope: Service providers only
     */
    public function scopeServiceProviders($query)
    {
        return $query->where('user_type', 'service_provider');
    }

    /**
     * Scope: Landlords only
     */
    public function scopeLandlords($query)
    {
        return $query->where('user_type', 'landlord');
    }

    /**
     * Scope: Admins only
     */
    public function scopeAdmins($query)
    {
        return $query->where('user_type', 'admin');
    }

    /**
     * Check if user is a tenant
     */
    public function isTenant(): bool
    {
        return $this->user_type === 'tenant';
    }

    /**
     * Check if user is a service provider
     */
    public function isServiceProvider(): bool
    {
        return $this->user_type === 'service_provider';
    }

    /**
     * Check if user is a landlord
     */
    public function isLandlord(): bool
    {
        return $this->user_type === 'landlord';
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->user_type === 'admin';
    }
}
