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
        Schema::create('officer_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique()->comment('Email officer');
            $table->string('name')->comment('Nama lengkap officer');
            $table->enum('type', ['PML', 'PCL'])->comment('Jenis officer');
            $table->timestamps();

            $table->index('email');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officer_mappings');
    }
};
