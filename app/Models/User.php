<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable;

    protected $fillable = [
        'id',
        'full_name', // Crucial: Must be present!
        'email',
        'phone',
        'password',  // Or 'password_hash' depending on your column choice
        'role',
        'barangay_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    // Role helpers
    public function isAdmin(): bool      { return $this->role === 'admin'; }
    public function isBarangay(): bool   { return $this->role === 'barangay'; }
    public function isCollector(): bool  { return $this->role === 'collector'; }
    public function isUser(): bool       { return $this->role === 'user'; }

    // Relationships
    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }

    public function pointAssignments(): HasMany
    {
        return $this->hasMany(UserPointAssignment::class);
    }

    public function garbagePoints()
    {
        return $this->belongsToMany(GarbagePoint::class, 'user_point_assignments')
                    ->withPivot('is_active', 'assigned_at')
                    ->withTimestamps();
    }

    public function collectionSessions(): HasMany
    {
        return $this->hasMany(CollectionSession::class, 'collector_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function aiGarbageLogs(): HasMany
    {
        return $this->hasMany(AiGarbageLog::class);
    }
}