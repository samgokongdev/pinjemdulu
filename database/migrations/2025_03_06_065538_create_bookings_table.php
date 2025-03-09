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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_peminjam');
            $table->string('seksi')->nullable();
            $table->text('keperluan');
            $table->date('tanggal_booking');
            $table->time('jam_mulai')->required();
            $table->time('jam_selesai')->required();
            $table->string('kode_booking')->nullable()->unique();
            $table->foreignId('aset_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_done')->default(0);
            $table->boolean('is_app')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
