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
       Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Relasi ke pelanggan
            $table->string('invoice_number')->unique(); // Nomor unik tagihan, misal INV-001
            $table->integer('amount'); // Total tagihan (sesuai harga paket)
            $table->date('due_date'); // Tanggal jatuh tempo
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid'); // Status tagihan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
