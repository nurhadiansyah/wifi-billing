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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('tripay_reference')->nullable();
            $table->string('payment_method')->nullable();
            $table->text('checkout_url')->nullable();
            $table->text('instructions')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['tripay_reference', 'payment_method', 'checkout_url', 'instructions']);
        });
    }
};
