<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom tarif bulanan (angka)
            $table->integer('tarif_bulanan')->nullable()->after('status');
            
            // Menambahkan kolom tanggal tagihan (angka 1 sampai 28)
            $table->integer('tanggal_tagihan')->nullable()->after('tarif_bulanan');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom jika migration di-rollback
            $table->dropColumn(['tarif_bulanan', 'tanggal_tagihan']);
        });
    }
};
