<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add mapping_pcl_pml to the type enum in PostgreSQL
        DB::statement("ALTER TABLE monitoring_imports DROP CONSTRAINT IF EXISTS monitoring_imports_type_check");
        DB::statement("ALTER TABLE monitoring_imports ADD CONSTRAINT monitoring_imports_type_check CHECK (type IN ('mapping_kecamatan', 'mapping_officer', 'mapping_pcl_pml', 'data_pml', 'data_pcl'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE monitoring_imports DROP CONSTRAINT IF EXISTS monitoring_imports_type_check");
        DB::statement("ALTER TABLE monitoring_imports ADD CONSTRAINT monitoring_imports_type_check CHECK (type IN ('mapping_kecamatan', 'mapping_officer', 'data_pml', 'data_pcl'))");
    }
};
