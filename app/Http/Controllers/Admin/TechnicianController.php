<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Technician;

class TechnicianController extends Controller
{
    // Menampilkan daftar teknisi
    public function index()
    {
        $technicians = Technician::latest()->get();
        return view('admin.technicians.index', compact('technicians'));
    }

    // Menyimpan teknisi baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'area'     => 'required|string|max:100',
        ]);

        Technician::create($request->all());

        return redirect()->back()->with('success', 'Teknisi baru berhasil ditambahkan!');
    }

    // Memperbarui data teknisi
    public function update(Request $request, Technician $technician)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'area'     => 'required|string|max:100',
        ]);

        $technician->update($request->all());

        return redirect()->back()->with('success', 'Data teknisi berhasil diperbarui!');
    }

    // Menghapus teknisi
    public function destroy(Technician $technician)
    {
        $technician->delete();

        return redirect()->back()->with('success', 'Teknisi berhasil dihapus!');
    }
}