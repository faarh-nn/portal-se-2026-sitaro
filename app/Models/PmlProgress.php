<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PmlProgress extends Model
{
    protected $table = 'pml_progress';

    protected $fillable = [
        'email',
        'name',
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
        if ($this->total_assignment == 0) {
            return 0;
        }

        return round(($this->submit / $this->total_assignment) * 100, 1);
    }

    /**
     * Calculate completion ratio.
     */
    public function getCompletionRatioAttribute(): float
    {
        if ($this->total_assignment == 0) {
            return 0;
        }

        return round(($this->completed / $this->total_assignment) * 100, 1);
    }
}
