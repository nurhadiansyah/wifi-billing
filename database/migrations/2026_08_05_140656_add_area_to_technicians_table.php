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
        Schema::table('technicians', function (Blueprint $table) {
            // Menambahkan kolom area setelah kolom phone
            $table->string('area')->nullable()->after('phone'); 
        });
    }

    public function down()
    {
        Schema::table('technicians', function (Blueprint $table) {
            $table->dropColumn('area');
        });
    }
};
