<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Ticket;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Pelanggan
        $totalCustomers = User::where('role', 'client')->count();
        $activeCustomers = User::where('role', 'client')->where('status', 'aktif')->count();
        $isolatedCustomers = User::where('role', 'client')->where('status', 'diisolir')->count();

        // Pendapatan Bulan Ini (Berdasarkan tagihan yang dibayar bulan ini)
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        $monthlyRevenue = Invoice::where('status', 'paid')
            ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // Total Tagihan Menunggak
        $unpaidInvoices = Invoice::where('status', 'unpaid')->sum('amount');

        // Tiket / Laporan Terbuka
        $openTicketsCount = Ticket::where('status', 'open')->count();

        // 5 Tagihan Terbaru
        $recentInvoices = Invoice::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 5 Laporan (Tiket) Terbaru
        $recentTickets = Ticket::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalCustomers',
            'activeCustomers',
            'isolatedCustomers',
            'monthlyRevenue',
            'unpaidInvoices',
            'openTicketsCount',
            'recentInvoices',
            'recentTickets'
        ));
    }
}
