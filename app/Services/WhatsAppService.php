<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use Carbon\Carbon;

class WhatsAppService
{
    public static function sendMessage($target, $message)
    {
        $setting = Setting::first();
        $token = $setting ? $setting->fonnte_token : env('FONNTE_TOKEN');
        
        if (empty($token)) {
            Log::warning("Fonnte Token is missing, skipping WhatsApp notification to " . $target);
            return false;
        }

        // Format target to international format if starts with 0
        if (substr($target, 0, 1) === '0') {
            $target = '62' . substr($target, 1);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62',
                'delay' => '2',
            ]);
            
            Log::info('Fonnte Response: ' . $response->body());
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Fonnte Error: ' . $e->getMessage());
            return false;
        }
    }

    public static function sendPaymentSuccessMessage($invoice)
    {
        if (!$invoice || !$invoice->user || empty($invoice->user->phone)) {
            return false;
        }

        $amount = number_format($invoice->amount, 0, ',', '.');
        $dueDateObj = Carbon::parse($invoice->due_date);
        $dueDateStr = $dueDateObj->format('d/m/Y');
        $periodEndStr = $dueDateObj->copy()->addMonth()->format('d/m/Y');
        $packageName = $invoice->user->package ? $invoice->user->package->name : 'Paket Internet';
        $paymentDate = Carbon::now()->format('d/m/Y H:i');

        $message = "Halo Bapak/Ibu {$invoice->user->name},\n\n"
                 . "Terima kasih, pembayaran tagihan internet Anda telah kami terima dengan rincian sebagai berikut:\n\n"
                 . "No. Invoice: {$invoice->invoice_number}\n"
                 . "Item: Internet {$invoice->user->name} - {$packageName}\n"
                 . "Periode: {$dueDateStr} - {$periodEndStr}\n"
                 . "Total Pembayaran: Rp {$amount}\n"
                 . "Tanggal Bayar: {$paymentDate}\n"
                 . "Status: LUNAS\n\n"
                 . "Terima kasih atas kepercayaan Anda menggunakan layanan Dreamnet.\n\n"
                 . "Ini adalah pesan otomatis - mohon untuk tidak membalas pesan ini.";

        return self::sendMessage($invoice->user->phone, $message);
    }
}
