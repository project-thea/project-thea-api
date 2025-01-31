<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Test extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * Get a test that belongs to a disease.
     */
    public function disease()
    {
        return $this->belongsTo(Disease::class, 'disease_id');
    }

    /**
     * Get a test that belongs to a subject.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * A test consists of many sample test trackers.
     */
    public function sample_test_trackings()
    {
        return $this->hasMany(SampleTestTracking::class);
    }

    /**
     * Get a test that belongs to a status.
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
