<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Mengubah perilaku foreign key pada tabel meetings dan meeting_questions:
 *
 * SEBELUM: cascadeOnDelete → rapat & pertanyaan ikut terhapus saat user dihapus
 * SESUDAH: nullOnDelete    → rapat & pertanyaan tetap ada, kolom user-nya jadi NULL
 *
 * Konsep: Rapat adalah dokumen permanen. User bisa dihapus (pindah kerja, resign),
 * tapi rekam jejak rapat harus tetap tersimpan.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── Tabel meetings ──
        Schema::table('meetings', function (Blueprint $table) {
            // Hapus foreign key lama
            $table->dropForeign(['created_by']);
            $table->dropForeign(['notulen']);

            // Ubah kolom jadi nullable
            $table->foreignId('created_by')->nullable()->change();
            $table->foreignId('notulen')->nullable()->change();

            // Buat ulang foreign key dengan nullOnDelete
            $table->foreign('created_by')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->foreign('notulen')
                ->references('id')->on('users')
                ->nullOnDelete();
        });

        // ── Tabel meeting_questions ──
        Schema::table('meeting_questions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);

            $table->foreignId('user_id')->nullable()->change();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        // Rollback: kembalikan ke cascadeOnDelete
        Schema::table('meetings', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['notulen']);

            $table->foreignId('created_by')->nullable(false)->change();
            $table->foreignId('notulen')->nullable(false)->change();

            $table->foreign('created_by')
                ->references('id')->on('users')
                ->cascadeOnDelete();

            $table->foreign('notulen')
                ->references('id')->on('users')
                ->cascadeOnDelete();
        });

        Schema::table('meeting_questions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);

            $table->foreignId('user_id')->nullable(false)->change();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->cascadeOnDelete();
        });
    }
};
