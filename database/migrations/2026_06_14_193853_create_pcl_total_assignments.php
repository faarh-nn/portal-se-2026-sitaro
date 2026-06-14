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
        Schema::create('pcl_total_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique()->comment('Email PCL');
            $table->unsignedInteger('total_assignment')->default(0)->comment('Total tugas PCL');
            $table->timestamps();

            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pcl_total_assignments');
    }
};
