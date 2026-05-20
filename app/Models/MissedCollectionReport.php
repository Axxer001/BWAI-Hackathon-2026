<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class MissedCollectionReport
 *
 * Represents a user-submitted report of a missed garbage pickup
 * at a specific collection point. Includes a photo, user notes,
 * and an AI-generated verification analysis for barangay dashboard review.
 */
class MissedCollectionReport extends Model
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'session_id',
        'collection_point_id',
        'reported_by',
        'photo_url',
        'notes',
        'status',
    ];

    /**
     * Get the user who submitted this missed collection report.
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Get the collection point associated with this report.
     */
    public function collectionPoint(): BelongsTo
    {
        return $this->belongsTo(CollectionPoint::class);
    }
}
