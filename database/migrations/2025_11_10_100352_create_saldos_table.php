<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saldos', function (Blueprint $table) {
            $table->id();
            $table->string('bulan', 7); // Format: YYYY-MM
            $table->decimal('saldo_awal', 10, 2)->default(0);
            $table->decimal('total_masuk', 10, 2)->default(0);
            $table->decimal('total_keluar', 10, 2)->default(0);
            $table->decimal('saldo_akhir', 10, 2)->default(0);
            $table->timestamps();
            
            $table->unique('bulan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saldos');
    }
};