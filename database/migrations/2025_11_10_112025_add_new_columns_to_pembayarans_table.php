<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->string('bukti_transfer')->nullable()->after('metode');
            $table->enum('status_approval', ['pending', 'approved', 'rejected'])->default('pending')->after('bukti_transfer');
            $table->text('catatan_admin')->nullable()->after('status_approval');
            $table->timestamp('tanggal_approval')->nullable()->after('catatan_admin');
        });
    }

    public function down()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn(['bukti_transfer', 'status_approval', 'catatan_admin', 'tanggal_approval']);
        });
    }
};