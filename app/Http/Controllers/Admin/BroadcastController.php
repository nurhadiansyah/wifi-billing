<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BroadcastController extends Controller
{
    // Menampilkan halaman form broadcast
    public function index()
    {
        // Mengambil daftar NAS yang unik dan tidak null
        $nasList = User::where('role', 'client')
            ->whereNotNull('router_nas')
            ->where('router_nas', '!=', '')
            ->distinct()
            ->pluck('router_nas');

        return view('admin.customers.broadcast', compact('nasList'));
    }

    // Memproses pengiriman pesan broadcast
    public function send(Request $request)
    {
        $request->validate([
            'nas' => 'required|string',
            'message' => 'required|string',
        ]);

        // Ambil pelanggan (client) yang aktif dan berada di NAS yang dipilih
        $customers = User::where('role', 'client')
            ->where('status', 'aktif')
            ->where('router_nas', $request->nas)
            ->get();

        if ($customers->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada pelanggan aktif di NAS tersebut.');
        }

        $targets = [];
        foreach ($customers as $customer) {
            if ($customer->phone) {
                $phone = $customer->phone;
                // Ubah 0 menjadi 62 di awal string
                if (substr($phone, 0, 1) === '0') {
                    $phone = '62' . substr($phone, 1);
                }
                $targets[] = $phone;
            }
        }

        if (empty($targets)) {
            return redirect()->back()->with('error', 'Pelanggan di NAS tersebut tidak memiliki nomor telepon.');
        }

        // Gabungkan nomor telepon dengan koma untuk Fonnte
        $targetString = implode(',', $targets);
        $message = $request->message;

        $success = $this->sendFonnteMessage($targetString, $message);

        if ($success) {
            $count = count($targets);
            return redirect()->back()->with('success', "Pesan broadcast berhasil dikirim ke $count pelanggan di NAS {$request->nas}.");
        } else {
            return redirect()->back()->with('error', 'Gagal mengirim pesan broadcast. Pastikan Token Fonnte disetel di .env dengan benar.');
        }
    }

    private function sendFonnteMessage($target, $message)
    {
        $setting = \App\Models\Setting::first();
        $token = $setting ? $setting->fonnte_token : env('FONNTE_TOKEN');
        if (empty($token)) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62',
            ]);
            
            Log::info('Fonnte Broadcast Response: ' . $response->body());

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Fonnte Broadcast Error: ' . $e->getMessage());
            return false;
        }
    }
}
