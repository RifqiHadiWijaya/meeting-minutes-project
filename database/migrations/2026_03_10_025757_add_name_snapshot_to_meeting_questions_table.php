<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Menambah kolom snapshot nama penanya ke tabel meeting_questions.
 * Berlaku untuk pertanyaan maupun reply.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meeting_questions', function (Blueprint $table) {
            $table->string('user_name')->nullable()->after('user_id');
        });

        // Backfill data lama
        DB::statement('
            UPDATE meeting_questions mq
            LEFT JOIN users u ON u.id = mq.user_id
            SET mq.user_name = COALESCE(u.name, mq.user_name, "Tidak diketahui")
        ');
    }

    public function down(): void
    {
        Schema::table('meeting_questions', function (Blueprint $table) {
            $table->dropColumn('user_name');
        });
    }
};
