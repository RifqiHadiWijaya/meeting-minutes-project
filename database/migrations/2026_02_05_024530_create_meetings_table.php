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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('date');
            $table->time('time');
            $table->string('location');
            $table->text('participants');
            $table->text('agenda')->nullable();
            $table->text('discussion')->nullable();
            $table->enum('status', ['belum_dilaksanakan', 'sudah_dilaksanakan'])
                ->default('belum_dilaksanakan');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
