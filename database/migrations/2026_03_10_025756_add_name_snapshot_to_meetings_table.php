<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Menambah kolom snapshot nama pembuat dan notulis ke tabel meetings.
 *
 * Snapshot disimpan saat rapat pertama kali dibuat (store),
 * sehingga nama tetap tersedia meski user sudah dihapus.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meetings', function (Blueprint $table) {
            // Snapshot nama — disimpan saat rapat dibuat, tidak pernah NULL
            $table->string('creator_name')->nullable()->after('created_by');
            $table->string('notulen_name')->nullable()->after('notulen');
        });

        // Isi snapshot untuk data rapat yang sudah ada (backfill)
        // Ambil nama dari relasi user yang masih ada
        DB::statement('
            UPDATE meetings m
            LEFT JOIN users u1 ON u1.id = m.created_by
            LEFT JOIN users u2 ON u2.id = m.notulen
            SET
                m.creator_name = COALESCE(u1.name, m.creator_name, "Tidak diketahui"),
                m.notulen_name = COALESCE(u2.name, m.notulen_name, "Tidak diketahui")
        ');
    }

    public function down(): void
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->dropColumn(['creator_name', 'notulen_name']);
        });
    }
};
