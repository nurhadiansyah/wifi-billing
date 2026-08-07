<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;

class PackageController extends Controller
{
    // Menampilkan daftar paket
    public function index()
    {
        $packages = Package::all();
        return view('admin.packages.index', compact('packages'));
    }

    // Menyimpan paket baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'speed' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Package::create([
            'name' => $request->name,
            'speed' => $request->speed,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.paket.index')->with('success', 'Paket WiFi berhasil ditambahkan!');
    }

    // Memperbarui paket
    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'speed' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $package->update([
            'name' => $request->name,
            'speed' => $request->speed,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.paket.index')->with('success', 'Paket WiFi berhasil diperbarui!');
    }

    // Menghapus paket
    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        $package->delete();

        return redirect()->route('admin.paket.index')->with('success', 'Paket WiFi berhasil dihapus!');
    }
}