<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CollectionSchedule extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'barangay_id',
        'day_of_week',
        'collection_time',
        'frequency',
        'default_truck_id',
        'default_collector_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }

    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class, 'default_truck_id');
    }

    public function collector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'default_collector_id');
    }

    public function garbagePoints(): BelongsToMany
    {
        return $this->belongsToMany(GarbagePoint::class, 'collection_schedule_garbage_point', 'schedule_id', 'garbage_point_id')
                    ->withPivot('sequence')
                    ->orderByPivot('sequence', 'asc');
    }
}