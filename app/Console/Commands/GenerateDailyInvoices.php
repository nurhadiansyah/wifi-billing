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
        $today = Carbon::today();
        $currentDay = $today->day;
        
        // Handle end of month edge cases (e.g., if today is Feb 28, we might need to bill people with billing date 29, 30, 31)
        $isLastDayOfMonth = $today->copy()->endOfMonth()->isToday();
        
        $clientsQuery = User::where('role', 'client')
            ->whereNotNull('package_id')
            ->whereNotNull('tanggal_tagihan')
            ->with('package');

        if ($isLastDayOfMonth) {
            // Bill everyone whose billing date is today OR greater than today (since this month is shorter)
            $clientsQuery->where('tanggal_tagihan', '>=', $currentDay);
        } else {
            // Bill only those whose billing date is exactly today
            $clientsQuery->where('tanggal_tagihan', $currentDay);
        }

        $clients = $clientsQuery->get();
        $generatedCount = 0;

        foreach ($clients as $client) {
            // Check if an invoice for THIS MONTH already exists to prevent duplicates
            // An invoice is considered for this month if due_date is in the current month and year
            $exists = Invoice::where('user_id', $client->id)
                ->whereYear('due_date', $today->year)
                ->whereMonth('due_date', $today->month)
                ->exists();

            if (!$exists && $client->package) {
                // Generate Invoice
                $invoiceNumber = 'INV-' . $today->format('Ymd') . '-' . $client->id . '-' . rand(100, 999);
                
                $invoice = Invoice::create([
                    'user_id' => $client->id,
                    'invoice_number' => $invoiceNumber,
                    'amount' => $client->package->price,
                    'due_date' => $today->format('Y-m-d'), // Jatuh tempo hari yang sama
                    'status' => 'unpaid',
                ]);
                
                $generatedCount++;

                // Optional: Send WhatsApp using Fonnte API
                $this->sendWhatsAppNotification($client, $invoice);
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

        $amountFormatted = 'Rp ' . number_format($invoice->amount, 0, ',', '.');
        $dueDate = Carbon::parse($invoice->due_date)->translatedFormat('d F Y');

        $message = "Halo *{$client->name}*,\n\n";
        $message .= "Tagihan internet WiFi Anda bulan ini telah terbit:\n\n";
        $message .= "No. Tagihan: {$invoice->invoice_number}\n";
        $message .= "Paket: {$client->package->name}\n";
        $message .= "Total Tagihan: *{$amountFormatted}*\n";
        $message .= "Jatuh Tempo: *{$dueDate}*\n\n";
        $message .= "Mohon segera melakukan pembayaran agar koneksi internet Anda tetap lancar. Terima kasih!\n\n";
        $message .= "- Dreamnet Indonesia";

        $fonnteToken = env('FONNTE_TOKEN');
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
