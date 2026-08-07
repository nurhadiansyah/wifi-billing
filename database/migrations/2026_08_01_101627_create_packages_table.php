<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');       // Nama Paket (Contoh: Paket Bronze 10 Mbps)
            $table->string('speed');      // Kecepatan (Contoh: 10 Mbps)
            $table->decimal('price', 10, 2); // Harga (Contoh: 150000.00)
            $table->text('description')->nullable(); // Keterangan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
