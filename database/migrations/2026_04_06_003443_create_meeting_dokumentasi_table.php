<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meeting_dokumentasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')
                  ->constrained('meetings')
                  ->cascadeOnDelete(); // foto ikut terhapus kalau rapat dihapus
            $table->string('nama_file');   // nama asli file
            $table->string('path_file');   // path di storage/app/public
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_dokumentasi');
    }
};