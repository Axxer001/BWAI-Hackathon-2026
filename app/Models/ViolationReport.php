<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ViolationReport
 *
 * Represents a user-submitted report of illegal garbage dumping.
 * Each report includes a photo, GPS coordinates, and an AI-generated
 * verification analysis for barangay dashboard review.
 */
class ViolationReport extends Model
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reported_by',
        'barangay_id', 
        'address',
        'latitude',
        'longitude',
        'photo_url',
        'description',
        'status',
    ];

    /**
     * Get the user who submitted this violation report.
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Get the barangay this violation was reported in.
     */
    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }
}
