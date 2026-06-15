<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PclDailySubmit extends Model
{
    protected $table = 'pcl_daily_submits';

    protected $fillable = [
        'email',
        'name',
        'kecamatan',
        'daily_submit',
        'total_submit',
        'target_met',
        'data_date',
    ];

    protected $casts = [
        'kecamatan' => 'array',
        'daily_submit' => 'integer',
        'total_submit' => 'integer',
        'target_met' => 'boolean',
        'data_date' => 'date',
    ];

    /**
     * Get kecamatans as string separated by |.
     */
    public function getKecamatanStringAttribute(): string
    {
        if (empty($this->kecamatan)) {
            return '-';
        }

        return implode(' | ', $this->kecamatan);
    }

    /**
     * Check if the daily submit target is met (>=10).
     */
    public function isTargetMet(): bool
    {
        return $this->daily_submit >= 10;
    }
}
