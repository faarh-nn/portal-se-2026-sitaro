<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // PCL Progress - hapus respondent, tambah approve & reject
        Schema::table('pcl_progress', function (Blueprint $table) {
            $table->dropColumn('respondent');
            $table->unsignedInteger('approve')->default(0)->comment('Jumlah approved');
            $table->unsignedInteger('reject')->default(0)->comment('Jumlah rejected');
        });

        // PML Progress - hapus respondent, tambah approve & reject
        Schema::table('pml_progress', function (Blueprint $table) {
            $table->dropColumn('respondent');
            $table->unsignedInteger('approve')->default(0)->comment('Jumlah approved');
            $table->unsignedInteger('reject')->default(0)->comment('Jumlah rejected');
        });

        // Kecamatan Progress - tambah draft, approve & reject
        Schema::table('kecamatan_progress', function (Blueprint $table) {
            $table->unsignedInteger('draft')->default(0)->comment('Jumlah draft')->after('submit');
            $table->unsignedInteger('approve')->default(0)->comment('Jumlah approved')->after('draft');
            $table->unsignedInteger('reject')->default(0)->comment('Jumlah rejected')->after('approve');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // PCL Progress
        Schema::table('pcl_progress', function (Blueprint $table) {
            $table->unsignedInteger('respondent')->default(0)->comment('Jumlah respondent')->after('submit');
            $table->dropColumn(['approve', 'reject']);
        });

        // PML Progress
        Schema::table('pml_progress', function (Blueprint $table) {
            $table->unsignedInteger('respondent')->default(0)->comment('Jumlah respondent')->after('submit');
            $table->dropColumn(['approve', 'reject']);
        });

        // Kecamatan Progress
        Schema::table('kecamatan_progress', function (Blueprint $table) {
            $table->dropColumn(['draft', 'approve', 'reject']);
        });
    }
};
