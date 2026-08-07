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
            $table->string('router_user')->nullable()->after('phone');
            $table->string('router_password')->nullable()->after('router_user');
            $table->string('router_profile')->nullable()->after('router_password');
            
            // TAMBAHKAN BARIS INI UNTUK NAS
            $table->string('router_nas')->nullable()->after('router_profile'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
