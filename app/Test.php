<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get a test that belongs to a disease.
     */
    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }

    /**
     * Get a test that belongs to a subject.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}