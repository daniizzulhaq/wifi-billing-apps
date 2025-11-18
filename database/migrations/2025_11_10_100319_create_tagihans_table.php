<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained()->onDelete('cascade');
            $table->string('bulan', 7); // Format: YYYY-MM
            $table->decimal('nominal', 10, 2);
            $table->date('jatuh_tempo');
            $table->enum('status', ['belum_dibayar', 'lunas'])->default('belum_dibayar');
            $table->timestamps();
            
            // Mencegah duplikasi tagihan untuk bulan yang sama
            $table->unique(['pelanggan_id', 'bulan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};