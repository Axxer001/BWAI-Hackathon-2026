<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionSchedule extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'barangay_id',
        'day_of_week',
        'collection_time',
        'frequency',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }
}
