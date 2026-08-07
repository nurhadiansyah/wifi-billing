<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    // Menampilkan daftar laporan pelanggan
    public function index()
    {
        $tickets = Auth::user()->tickets()->latest()->get();
        return view('client.tickets.index', compact('tickets'));
    }

    // Menyimpan laporan baru
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Ticket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'description' => $request->description,
            'status' => 'pending', // Laporan baru otomatis berstatus pending
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil dikirim! Tim kami akan segera mengeceknya.');
    }
}