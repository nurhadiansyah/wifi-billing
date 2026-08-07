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
            $table->dropColumn('tarif_bulanan'); // Menghapus kolom
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('tarif_bulanan')->nullable(); // Mengembalikan jika di-rollback
        });
    }
};
