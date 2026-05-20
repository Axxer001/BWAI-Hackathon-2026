<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WasteAssessment
 * 
 * Represents a record of an AI-assessed waste item.
 * Used primarily for collecting analytics on identified waste types.
 */
class WasteAssessment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'barangay_id'];
}
