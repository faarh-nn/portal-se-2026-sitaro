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
        Schema::create('pcl_daily_submits', function (Blueprint $table) {
            $table->id();
            $table->string('email')->comment('Email PCL');
            $table->string('name')->nullable()->comment('Nama PCL');
            $table->json('kecamatan')->nullable()->comment('Array nama kecamatan');
            $table->unsignedInteger('daily_submit')->default(0)->comment('Submit harian (selisih dari import sebelumnya)');
            $table->unsignedInteger('total_submit')->default(0)->comment('Total submit kumulatif');
            $table->boolean('target_met')->default(false)->comment('Apakah target harian terpenuhi (>=10)');
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
        Schema::dropIfExists('pcl_daily_submits');
    }
};
