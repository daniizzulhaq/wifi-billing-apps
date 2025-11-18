<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('tagihans', function (Blueprint $table) {
            $table->enum('status', ['belum_lunas', 'lunas'])
                  ->default('belum_lunas')
                  ->change();
        });
    }

    public function down()
    {
        Schema::table('tagihans', function (Blueprint $table) {
            $table->enum('status', ['belum_dibayar', 'lunas'])
                  ->default('belum_dibayar')
                  ->change();
        });
    }
};
