<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonitoringImport extends Model
{
    protected $fillable = [
        'file_name',
        'type',
        'status',
        'error_message',
        'rows_imported',
        'imported_by',
        'imported_at',
    ];

    protected $casts = [
        'imported_at' => 'datetime',
        'rows_imported' => 'integer',
    ];

    /**
     * Get the user who imported.
     */
    public function importedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'imported_by');
    }

    /**
     * Get PCL progress data from this import.
     */
    public function pclProgress(): HasMany
    {
        return $this->hasMany(PclProgress::class, 'import_id');
    }

    /**
     * Get PML progress data from this import.
     */
    public function pmlProgress(): HasMany
    {
        return $this->hasMany(PmlProgress::class, 'import_id');
    }

    /**
     * Get kecamatan progress data from this import.
     */
    public function kecamatanProgress(): HasMany
    {
        return $this->hasMany(KecamatanProgress::class, 'import_id');
    }

    /**
     * Mark import as completed.
     */
    public function markCompleted(int $rowsImported): void
    {
        $this->update([
            'status' => 'completed',
            'rows_imported' => $rowsImported,
        ]);
    }

    /**
     * Mark import as failed.
     */
    public function markFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }
}
