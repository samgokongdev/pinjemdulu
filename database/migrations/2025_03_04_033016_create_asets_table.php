<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_aset');
            $table->string('nomor_identifikasi_aset');
            $table->foreignId('kategori_id')->constrained()->onDelete('cascade');
            $table->integer('tahun_pengadaan')->nullable();
            $table->boolean('is_ready')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        DB::table('asets')->insert([
            ['nama_aset' => 'Toyota Avanza', 'nomor_identifikasi_aset' => 'B1234XXX', 'kategori_id' => 1, 'tahun_pengadaan' => 2020, 'is_ready' => 1, 'keterangan' => "Hitam", 'created_at' => now(), 'updated_at' => now()],
            ['nama_aset' => 'Toyota Rush', 'nomor_identifikasi_aset' => 'B1234ZZZ', 'kategori_id' => 1, 'tahun_pengadaan' => 2021, 'is_ready' => 1, 'keterangan' => "Putih", 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asets');
    }
};
