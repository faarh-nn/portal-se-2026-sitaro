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
        Schema::create('pml_total_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique()->comment('Email PML');
            $table->unsignedInteger('total_assignment')->default(0)->comment('Total tugas PML');
            $table->timestamps();

            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pml_total_assignments');
    }
};
