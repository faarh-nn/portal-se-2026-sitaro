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
        Schema::create('assignment_history_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('pml_email')->index();
            $table->string('pcl_email')->index();
            $table->unsignedBigInteger('import_id')->index();
            $table->date('imported_at')->index();
            $table->timestamps();

            $table->foreign('import_id')->references('id')->on('monitoring_imports')->onDelete('cascade');
        });

        Schema::create('assignment_history_status_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assignment_history_status_id')->index();
            $table->string('status')->index();
            $table->dateTime('tanggal')->index();
            $table->timestamps();

            $table->foreign('assignment_history_status_id')
                ->references('id')
                ->on('assignment_history_statuses')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_history_status_items');
        Schema::dropIfExists('assignment_history_statuses');
    }
};
