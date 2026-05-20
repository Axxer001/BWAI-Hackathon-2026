<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GarbagePoint extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'address',
        'barangay_id',
        'is_active',
    ];

    protected $casts = [
        'latitude'  => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_active' => 'boolean',
    ];

    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }

    public function userAssignments(): HasMany
    {
        return $this->hasMany(UserPointAssignment::class);
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'user_point_assignments')
                    ->withPivot('is_active', 'assigned_at')
                    ->withTimestamps();
    }

    public function sessionPoints(): HasMany
    {
        return $this->hasMany(SessionPoint::class);
    }

    public function aiGarbageLogs(): HasMany
    {
        return $this->hasMany(AiGarbageLog::class);
    }
}