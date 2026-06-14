<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KecamatanMapping extends Model
{
    protected $fillable = [
        'kode',
        'kecamatan',
    ];

    protected $casts = [
        'kode' => 'string',
    ];

    /**
     * Get progress data for this kecamatan.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(KecamatanProgress::class, 'kecamatan', 'kecamatan');
    }

    /**
     * Get latest progress for this kecamatan.
     */
    public function latestProgress()
    {
        return $this->hasOne(KecamatanProgress::class, 'kecamatan', 'kecamatan')
            ->orderByDesc('data_date')
            ->limit(1);
    }
}
