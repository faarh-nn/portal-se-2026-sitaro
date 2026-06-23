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
        // Drop existing constraint first before recreating
        DB::statement('ALTER TABLE monitoring_imports DROP CONSTRAINT IF EXISTS monitoring_imports_type_check');
        
        DB::statement('ALTER TABLE monitoring_imports ALTER COLUMN type TYPE VARCHAR(50)');
        DB::statement("ALTER TABLE monitoring_imports ADD CONSTRAINT monitoring_imports_type_check
            CHECK (type IN ('mapping_kecamatan', 'mapping_officer', 'data_pml', 'data_pcl', 'mapping_pcl_pml', 'assignment_history'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE monitoring_imports DROP CONSTRAINT IF EXISTS monitoring_imports_type_check');
        DB::statement('ALTER TABLE monitoring_imports ALTER COLUMN type TYPE VARCHAR(50)');
        DB::statement("ALTER TABLE monitoring_imports ADD CONSTRAINT monitoring_imports_type_check
            CHECK (type IN ('mapping_kecamatan', 'mapping_officer', 'data_pml', 'data_pcl'))");
    }
};