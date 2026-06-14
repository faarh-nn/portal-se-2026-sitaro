<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OfficerMapping extends Model
{
    protected $fillable = [
        'email',
        'name',
        'type',
    ];

    protected $casts = [
        'type' => 'string',
    ];

    /**
     * Scope for PML officers.
     */
    public function scopePml($query)
    {
        return $query->where('type', 'PML');
    }

    /**
     * Scope for PCL officers.
     */
    public function scopePcl($query)
    {
        return $query->where('type', 'PCL');
    }

    /**
     * Get PML progress data.
     */
    public function pmlProgress(): HasMany
    {
        return $this->hasMany(PmlProgress::class, 'email', 'email');
    }

    /**
     * Get PCL progress data.
     */
    public function pclProgress(): HasMany
    {
        return $this->hasMany(PclProgress::class, 'email', 'email');
    }
}
