<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        // Ambil data tagihan khusus untuk pelanggan yang sedang login
        $invoices = Auth::user()->invoices()->orderBy('due_date', 'desc')->get();
        
        return view('client.invoices.index', compact('invoices'));
    }
}