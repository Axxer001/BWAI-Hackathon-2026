<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectionPoint extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'barangay_id',
        'name',
        'latitude',
        'longitude',
        'address',
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

    public function missedCollectionReports(): HasMany
    {
        return $this->hasMany(MissedCollectionReport::class);
    }
}
