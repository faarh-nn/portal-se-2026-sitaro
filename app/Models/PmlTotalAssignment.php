<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PmlTotalAssignment extends Model
{
    protected $table = 'pml_total_assignments';

    protected $fillable = [
        'email',
        'total_assignment',
    ];

    protected $casts = [
        'total_assignment' => 'integer',
    ];

    /**
     * Get the officer.
     */
    public function officer(): BelongsTo
    {
        return $this->belongsTo(OfficerMapping::class, 'email', 'email');
    }
}
