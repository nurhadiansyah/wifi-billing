<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom khusus untuk sistem WiFi kita
            $table->string('phone')->nullable()->after('email'); 
            $table->enum('role', ['admin', 'client'])->default('client')->after('password');
            $table->text('address')->nullable()->after('role');
            $table->enum('status', ['aktif', 'diisolir'])->default('aktif')->after('address');
            
            // Relasi ke ID Paket (paket_id)
            $table->foreignId('package_id')->nullable()->constrained('packages')->nullOnDelete()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->dropColumn(['phone', 'role', 'address', 'status', 'package_id']);
        });
    }
};