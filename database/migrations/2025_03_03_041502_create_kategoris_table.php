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
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->timestamps();
        });

        DB::table('kategoris')->insert([
            ['nama' => 'mobil', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'motor', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'ruangan', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'kamera', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategoris');
    }
};
