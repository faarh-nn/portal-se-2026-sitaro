<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentHistoryStatusItem extends Model
{
    protected $table = 'assignment_history_status_items';

    protected $fillable = [
        'assignment_history_status_id',
        'status',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    /**
     * Get the parent assignment history status.
     */
    public function assignmentHistoryStatus(): BelongsTo
    {
        return $this->belongsTo(AssignmentHistoryStatus::class, 'assignment_history_status_id');
    }
}
