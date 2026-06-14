<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PmlProgress extends Model
{
    protected $table = 'pml_progress';

    protected $fillable = [
        'email',
        'name',
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
     * Get the total assignment record.
     */
    public function totalAssignment(): HasOne
    {
        return $this->hasOne(PmlTotalAssignment::class, 'email', 'email');
    }

    /**
     * Get officer name from mapping.
     */
    public function getOfficerNameAttribute(): ?string
    {
        if ($this->name) {
            return $this->name;
        }

        return OfficerMapping::where('email', $this->email)->value('name');
    }

    /**
     * Calculate submit ratio.
     */
    public function getSubmitRatioAttribute(): float
    {
        $totalAssignment = $this->totalAssignment?->total_assignment ?? 0;

        if ($totalAssignment == 0) {
            return 0;
        }

        return round(($this->submit / $totalAssignment) * 100, 1);
    }

    /**
     * Calculate completion ratio.
     */
    public function getCompletionRatioAttribute(): float
    {
        $totalAssignment = $this->totalAssignment?->total_assignment ?? 0;

        if ($totalAssignment == 0) {
            return 0;
        }

        return round(($this->completed / $totalAssignment) * 100, 1);
    }
}
