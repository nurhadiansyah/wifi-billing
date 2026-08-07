<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    // Menampilkan semua laporan dari semua pelanggan
    public function index()
    {
        $tickets = Ticket::with('user')->latest()->get();
        $technicians = \App\Models\Technician::all(); // Ambil semua data teknisi
        return view('admin.tickets.index', compact('tickets', 'technicians'));
    }

    // Memperbarui status laporan
    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:pending,proses,selesai',
        ]);

        $ticket->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Status laporan berhasil diperbarui!');
    }
}