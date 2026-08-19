<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GenerateDailyInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:generate-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate invoices automatically for clients whose billing date matches today';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $setting = \App\Models\Setting::first();
        $daysBefore = $setting ? ($setting->reminder_days_before ?? 0) : 0;
        
        $today = Carbon::today();
        $targetDate = $today->copy()->addDays($daysBefore);
        $targetDay = $targetDate->day;
        
        // Handle end of month edge cases (e.g., if target date is Feb 28, we might need to bill people with billing date 29, 30, 31)
        $isLastDayOfMonth = $targetDate->copy()->endOfMonth()->isSameDay($targetDate);
        
        $clientsQuery = User::where('role', 'client')
            ->whereNotNull('package_id')
            ->whereNotNull('tanggal_tagihan')
            ->with('package');

        if ($isLastDayOfMonth) {
            // Bill everyone whose billing date is targetDay OR greater (since this month is shorter)
            $clientsQuery->where('tanggal_tagihan', '>=', $targetDay);
        } else {
            // Bill only those whose billing date is exactly targetDay
            $clientsQuery->where('tanggal_tagihan', $targetDay);
        }

        $clients = $clientsQuery->get();
        $generatedCount = 0;

        foreach ($clients as $client) {
            // Check if an invoice for THIS MONTH already exists to prevent duplicates
            // An invoice is considered for this month if due_date is in the current month and year
            $exists = Invoice::where('user_id', $client->id)
                ->whereYear('due_date', $targetDate->year)
                ->whereMonth('due_date', $targetDate->month)
                ->exists();

            if (!$exists && $client->package) {
                // Generate Invoice
                $invoiceNumber = 'INV-' . $targetDate->format('Ymd') . '-' . $client->id . '-' . rand(100, 999);
                
                $invoice = Invoice::create([
                    'user_id' => $client->id,
                    'invoice_number' => $invoiceNumber,
                    'amount' => $client->package->price,
                    'due_date' => $targetDate->format('Y-m-d'), // Jatuh tempo sesuai target date
                    'status' => 'unpaid',
                ]);
                
                $generatedCount++;

                // Optional: Send WhatsApp using Fonnte API
                $setting = \App\Models\Setting::first();
                if ($setting && $setting->auto_reminder) {
                    $this->sendWhatsAppNotification($client, $invoice);
                }
            }
        }

        $this->info("Successfully generated $generatedCount invoices today.");
        Log::info("CRON: Generated $generatedCount invoices for date " . $today->format('Y-m-d'));

        return 0;
    }

    private function sendWhatsAppNotification($client, $invoice)
    {
        if (empty($client->phone)) return;

        // Ensure phone number starts with country code, e.g., 62
        $phone = $client->phone;
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $amount = number_format($invoice->amount, 0, ',', '.');
        $dueDateObj = \Carbon\Carbon::parse($invoice->due_date);
        $dueDateStr = $dueDateObj->format('d/m/Y');
        $periodEndStr = $dueDateObj->copy()->addMonth()->format('d/m/Y');
        $packageName = $client->package ? $client->package->name : 'Paket Internet';

        $message = "Hai Pelanggan Setia Dreamnet\n\n"
                 . "Yth. Bapak/Ibu {$client->name}\n\n"
                 . "Terima kasih atas kesetiaan & kepercayaan Anda memilih layanan Dreamnet, Kami informasikan Invoice anda telah terbit dan dapat dibayarkan, berikut rinciannya :\n"
                 . "ID Pelanggan: {$client->phone}\n"
                 . "Username Aplikasi: {$client->email}\n"
                 . "Username Internet: {$client->router_user}\n"
                 . "Password Internet: {$client->router_password}\n"
                 . "Nomor Invoice: {$invoice->invoice_number}\n"
                 . "Amount: Rp {$amount}\n"
                 . "PPN: 0\n"
                 . "Discount: 0\n"
                 . "Total: Rp {$amount}\n"
                 . "Item: Internet {$client->name} - {$packageName}\n"
                 . "Jatuh tempo: {$dueDateStr}\n"
                 . "Period: {$dueDateStr} - {$periodEndStr}\n"
                 . "Mohon segera lakukan pembayaran sebelum jatuh tempo\n\n"
                 . "Terima kasih.\n\n"
                 . "silahkan melakukan pembayaran di website resmi kami 🙏🏻 di dreamnetindonesia\n\n\n"
                 . "Pihak Dreamnet Tidak menerima Pembayaran secara Tunai melalui Marketing,Teknisi  Atupun Agen Lainya  yang \n"
                 . "mengatasnamakan Dreamnet\n\n"
                 . "Ini adalah pesan otomatis - mohon untuk tidak membalas langsung ke pesan ini";

        $setting = \App\Models\Setting::first();
        $fonnteToken = $setting ? $setting->fonnte_token : env('FONNTE_TOKEN');
        if (empty($fonnteToken)) {
            Log::warning("Fonnte Token is missing, skipping WhatsApp notification for " . $client->name);
            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $fonnteToken
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
                'delay' => '2',
            ]);

            Log::info("WhatsApp Notification sent to {$client->name} ({$phone}) - Response: " . $response->body());
        } catch (\Exception $e) {
            Log::error("Failed to send WA to {$client->name}: " . $e->getMessage());
        }
    }
}
