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
        Schema::create('kecamatan_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 6)->unique()->comment('6-digit kecamatan code');
            $table->string('kecamatan')->comment('Nama kecamatan');
            $table->timestamps();

            $table->index('kode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kecamatan_mappings');
    }
};
