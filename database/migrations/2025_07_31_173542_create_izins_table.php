<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('izins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('instansi_id')->constrained()->onDelete('cascade');
            $table->string('bukti_izin');
            $table->enum('status', ['diterima', 'tidak_diterima', 'belum_diverifikasi'])->default('belum_diverifikasi');
            $table->text('keterangan_ditolak')->nullable();
            $table->date('tanggal');
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izins');
    }
};
