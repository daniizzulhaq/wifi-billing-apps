<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kas', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->date('tanggal');
            $table->decimal('jumlah', 10, 2);
            $table->string('kategori', 50); // pembayaran_tagihan, operasional, pemeliharaan, dll
            $table->text('keterangan')->nullable();
            $table->foreignId('pembayaran_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kas');
    }
};