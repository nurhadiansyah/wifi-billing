<?php
$privateKey = 'u5W7f-Xkc7l-uicYA-fXnCY-okC5k';
$data = [
    'reference' => 'TEST12345',
    'merchant_ref' => 'INV-TEST',
    'status' => 'PAID',
    'payment_method' => 'BCA Virtual Account'
];
$json = json_encode($data);
$signature = hash_hmac('sha256', $json, $privateKey);

$ch = curl_init('https://dreamnetindonesia.com/tripay/callback');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'x-callback-event: payment_status',
    'x-callback-signature: ' . $signature
]);
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "HTTP $httpcode\n";
echo $response;
