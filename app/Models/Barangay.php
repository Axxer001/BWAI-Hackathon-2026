<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barangay extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'city',
        'contact_email',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function garbagePoints(): HasMany
    {
        return $this->hasMany(GarbagePoint::class);
    }

    public function collectionSessions(): HasMany
    {
        return $this->hasMany(CollectionSession::class);
    }
}