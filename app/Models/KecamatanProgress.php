<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KecamatanProgress extends Model
{
    protected $table = 'kecamatan_progress';

    protected $fillable = [
        'kecamatan',
        'kode',
        'total_assignment',
        'open',
        'draft',
        'submit',
        'approve',
        'reject',
        'completed',
        'import_id',
        'data_date',
    ];

    protected $casts = [
        'total_assignment' => 'integer',
        'open' => 'integer',
        'draft' => 'integer',
        'submit' => 'integer',
        'approve' => 'integer',
        'reject' => 'integer',
        'completed' => 'integer',
        'data_date' => 'date',
    ];

    /**
     * Get the import record.
     */
    public function import(): BelongsTo
    {
        return $this->belongsTo(MonitoringImport::class, 'import_id');
    }

    /**
     * Get KecamatanMapping.
     */
    public function mapping()
    {
        return $this->belongsTo(KecamatanMapping::class, 'kecamatan', 'kecamatan');
    }

    /**
     * Calculate progress percentage.
     */
    public function getProgressPercentAttribute(): float
    {
        if ($this->total_assignment == 0) {
            return 0;
        }

        return round(($this->completed / $this->total_assignment) * 100, 1);
    }
}
