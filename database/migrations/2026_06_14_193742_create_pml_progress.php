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
        Schema::create('pml_progress', function (Blueprint $table) {
            $table->id();
            $table->string('email')->comment('Email PML');
            $table->string('name')->nullable()->comment('Nama PML dari mapping');
            $table->unsignedInteger('open')->default(0)->comment('Jumlah open');
            $table->unsignedInteger('draft')->default(0)->comment('Jumlah draft');
            $table->unsignedInteger('submit')->default(0)->comment('Jumlah submitted');
            $table->unsignedInteger('approve')->default(0)->comment('Jumlah approved');
            $table->unsignedInteger('reject')->default(0)->comment('Jumlah rejected');
            $table->unsignedInteger('completed')->default(0)->comment('Jumlah completed');
            $table->foreignId('import_id')->constrained('monitoring_imports')->cascadeOnDelete();
            $table->timestamp('data_date')->comment('Tanggal data monitoring');
            $table->timestamps();

            $table->index('email');
            $table->index('data_date');
            $table->index(['email', 'data_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pml_progress');
    }
};
