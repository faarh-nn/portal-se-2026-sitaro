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
        Schema::table('kecamatan_mappings', function (Blueprint $table) {
            $table->string('kode', 10)->change()->comment('7-digit kecamatan code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kecamatan_mappings', function (Blueprint $table) {
            $table->string('kode', 6)->change();
        });
    }
};
