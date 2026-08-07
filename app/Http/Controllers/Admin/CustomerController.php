<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Package;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CustomersImport;

class CustomerController extends Controller
{
    // Menampilkan tabel pelanggan beserta daftar paket untuk pilihan di modal
    public function index(Request $request)
    {
        $query = User::where('role', 'client'); // atau sesuaikan dengan cara Anda memfilter role pelanggan

        // Jika admin mengetik sesuatu di kotak pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Jika admin memilih filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $customers = $query->with('package')->get();
        $packages  = Package::all();

        return view('admin.customers.index', compact('customers', 'packages'));
    }

    // Memproses tambah pelanggan
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'address' => 'nullable|string',
            'package_id' => 'nullable|exists:packages,id', // Validasi paket
            
            // --- TAMBAHAN KITA: Validasi Tanggal Tagihan ---
            'tanggal_tagihan' => 'required|integer|min:1|max:28', 
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'package_id' => $request->package_id, // Simpan paket pelanggan
            
            // --- TAMBAHAN KITA: Simpan Tanggal Tagihan ---
            'tanggal_tagihan' => $request->tanggal_tagihan, 
            
            'password' => bcrypt('password123'),
            'role' => 'client',
            'status' => 'aktif',
        ]);

        return redirect()->route('admin.pelanggan.index')->with('success', 'Data Pelanggan berhasil ditambahkan!');
    }

    // Memproses update pelanggan
    public function update(Request $request, $id)
    {
        $customer = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:15',
            'address' => 'nullable|string',
            'package_id' => 'nullable|exists:packages,id',
            'tanggal_tagihan' => 'required|integer|min:1|max:28', 
            
            // --- TAMBAHAN KITA: Validasi Status Akun ---
            'status' => 'required|in:aktif,diisolir',
        ]);

        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'package_id' => $request->package_id,
            'tanggal_tagihan' => $request->tanggal_tagihan,
            
            // --- TAMBAHAN KITA: Update Status Akun ---
            'status' => $request->status, 
        ]);

        return redirect()->route('admin.pelanggan.index')->with('success', 'Data Pelanggan berhasil diperbarui!');
    }

    // Menghapus pelanggan
    public function destroy($id)
    {
        $customer = User::findOrFail($id);
        $customer->delete();

        return redirect()->route('admin.pelanggan.index')->with('success', 'Data Pelanggan berhasil dihapus!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv'
        ]);

        $import = new CustomersImport();
        Excel::import($import, $request->file('file_excel'));

        // Siapkan pesan notifikasi
        $pesan = "Import Selesai! {$import->berhasil} data berhasil ditambahkan.";
        
        // Jika ada yang duplikat, tambahkan peringatan ke dalam pesan
        if ($import->duplikat > 0) {
            $namaDouble = implode(', ', $import->namaDuplikat);
            $pesan .= " Namun, ada {$import->duplikat} data yang dilewati karena duplikat (Nama/Email sudah ada): {$namaDouble}.";
            return back()->with('success', $pesan); // Bisa pakai warna kuning/warning jika mau
        }

        return back()->with('success', $pesan);
    }
}