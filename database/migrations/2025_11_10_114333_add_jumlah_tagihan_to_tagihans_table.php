<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('tagihans', function (Blueprint $table) {
            $table->decimal('jumlah_tagihan', 12, 2)->after('bulan');
            $table->decimal('denda', 12, 2)->default(0)->after('jumlah_tagihan');
        });
    }

    public function down()
    {
        Schema::table('tagihans', function (Blueprint $table) {
            $table->dropColumn(['jumlah_tagihan', 'denda']);
        });
    }
};
