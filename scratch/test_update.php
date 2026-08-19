<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$customer = App\Models\User::where('role', 'client')->first();
if (!$customer) {
    echo "No customer found\n";
    exit;
}

echo "Old day: " . $customer->tanggal_tagihan . "\n";

$updateData = [
    'tanggal_tagihan' => \Carbon\Carbon::parse('2026-08-25')->day,
];

$customer->update($updateData);

echo "New day: " . $customer->fresh()->tanggal_tagihan . "\n";
