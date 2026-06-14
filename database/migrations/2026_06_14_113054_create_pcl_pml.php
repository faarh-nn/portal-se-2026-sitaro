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
        Schema::create('pcl_pml', function (Blueprint $table) {
            $table->id();
            $table->string('pcl_email')->comment('Email PCL');
            $table->string('pml_email')->nullable()->comment('Email PML dari mapping');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pcl_pml');
    }
};
