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
        Schema::create('monitoring_imports', function (Blueprint $table) {
            $table->id();
            $table->string('file_name')->comment('Nama file yang diupload');
            $table->enum('type', ['mapping_kecamatan', 'mapping_officer', 'data_pml', 'data_pcl'])->comment('Tipe import');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->text('error_message')->nullable()->comment('Pesan error jika gagal');
            $table->unsignedInteger('rows_imported')->default(0)->comment('Jumlah row yang berhasil diimport');
            $table->foreignId('imported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('imported_at')->useCurrent();
            $table->timestamps();

            $table->index('type');
            $table->index('status');
            $table->index('imported_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_imports');
    }
};
