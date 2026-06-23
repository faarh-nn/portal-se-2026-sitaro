<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssignmentHistoryStatus extends Model
{
    protected $table = 'assignment_history_statuses';

    protected $fillable = [
        'pml_email',
        'pcl_email',
        'import_id',
        'imported_at',
    ];

    protected $casts = [
        'imported_at' => 'date',
    ];

    /**
     * Get the monitoring import record.
     */
    public function import(): BelongsTo
    {
        return $this->belongsTo(MonitoringImport::class, 'import_id');
    }

    /**
     * Get the history status items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(AssignmentHistoryStatusItem::class, 'assignment_history_status_id');
    }
}
