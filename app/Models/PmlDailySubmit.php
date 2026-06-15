<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PmlDailySubmit extends Model
{
    protected $table = 'pml_daily_submits';

    protected $fillable = [
        'email',
        'name',
        'daily_reject',
        'daily_approve',
        'daily_combined',
        'total_reject',
        'total_approve',
        'pcl_count',
        'target_met',
        'data_date',
    ];

    protected $casts = [
        'daily_reject' => 'integer',
        'daily_approve' => 'integer',
        'daily_combined' => 'integer',
        'total_reject' => 'integer',
        'total_approve' => 'integer',
        'pcl_count' => 'integer',
        'target_met' => 'boolean',
        'data_date' => 'date',
    ];

    /**
     * Calculate target threshold based on PCL count.
     * Target = 0.5 * 10 * pcl_count = 5 * pcl_count
     */
    public function getTargetThresholdAttribute(): int
    {
        return 5 * $this->pcl_count;
    }

    /**
     * Check if the daily combined value meets the target.
     */
    public function isTargetMet(): bool
    {
        return $this->daily_combined >= $this->target_threshold;
    }
}
