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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete(); // Relasi ke tagihan
            $table->string('reference')->unique(); // Kode unik dari Tripay
            $table->string('payment_method')->nullable(); // Misal: QRIS, Alfamart, OVO
            $table->integer('amount_paid'); // Total yang dibayarkan pelanggan (termasuk admin fee)
            $table->integer('fee')->default(0); // Biaya admin dari Tripay
            $table->enum('status', ['UNPAID', 'PAID', 'FAILED'])->default('UNPAID'); // Status asli dari Tripay
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
