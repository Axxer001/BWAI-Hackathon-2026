<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Truck extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'plate_number',
        'capacity_tons',
        'barangay_id',
        'is_active',
    ];

    protected $casts = [
        'capacity_tons' => 'decimal:2',
        'is_active'     => 'boolean',
    ];

    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }
}
