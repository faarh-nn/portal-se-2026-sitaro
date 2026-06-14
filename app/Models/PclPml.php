<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PclPml extends Model
{
    protected $table = 'pcl_pml';

    protected $fillable = [
        'pcl_email',
        'pml_email',
    ];

    /**
     * Get the PCL officer.
     */
    public function pcl(): BelongsTo
    {
        return $this->belongsTo(OfficerMapping::class, 'pcl_email', 'email');
    }

    /**
     * Get the PML officer.
     */
    public function pml(): BelongsTo
    {
        return $this->belongsTo(OfficerMapping::class, 'pml_email', 'email');
    }
}
