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
        Schema::create('pml_daily_submits', function (Blueprint $table) {
            $table->id();
            $table->string('email')->comment('Email PML');
            $table->string('name')->nullable()->comment('Nama PML');
            $table->unsignedInteger('daily_reject')->default(0)->comment('Reject harian');
            $table->unsignedInteger('daily_approve')->default(0)->comment('Approve harian');
            $table->unsignedInteger('daily_combined')->default(0)->comment('Reject + Approve harian');
            $table->unsignedInteger('total_reject')->default(0)->comment('Total reject kumulatif');
            $table->unsignedInteger('total_approve')->default(0)->comment('Total approve kumulatif');
            $table->unsignedInteger('pcl_count')->default(0)->comment('Jumlah PCL yang dibawahi');
            $table->boolean('target_met')->default(false)->comment('Apakah target harian terpenuhi');
            $table->date('data_date')->comment('Tanggal data');
            $table->timestamps();

            $table->unique(['email', 'data_date']);
            $table->index('email');
            $table->index('data_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pml_daily_submits');
    }
};
