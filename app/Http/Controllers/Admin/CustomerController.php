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

        // Jika admin memilih filter NAS
        if ($request->has('nas') && $request->nas != '') {
            $query->where('router_nas', $request->nas);
        }

        $customers = $query->with('package')->paginate(10);
        $packages  = Package::all();

        // Ambil daftar unik NAS untuk dropdown filter
        $nasOptions = User::where('role', 'client')->whereNotNull('router_nas')->where('router_nas', '!=', '')->distinct()->pluck('router_nas');

        return view('admin.customers.index', compact('customers', 'packages', 'nasOptions'));
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
            'tanggal_tagihan' => 'required|date', 
            'router_user' => 'nullable|string|max:255',
            'router_password' => 'nullable|string|max:255',
            'router_profile' => 'nullable|string|max:255',
            'router_nas' => 'nullable|string|max:255',
            'activation_date' => 'nullable|date',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'package_id' => $request->package_id, // Simpan paket pelanggan
            
            // --- TAMBAHAN KITA: Simpan Tanggal Tagihan ---
            'tanggal_tagihan' => $request->tanggal_tagihan, 
            
            'router_user' => $request->router_user,
            'router_password' => $request->router_password,
            'router_profile' => $request->router_profile,
            'router_nas' => $request->router_nas,
            'activation_date' => $request->activation_date,
            
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
            'phone' => 'required|string|max:25',
            'address' => 'nullable|string',
            'package_id' => 'nullable|exists:packages,id',
            'tanggal_tagihan' => 'required|date', 
            'status' => 'required|in:aktif,diisolir',
            'password' => 'nullable|string|min:6',
            'router_user' => 'nullable|string|max:255',
            'router_password' => 'nullable|string|max:255',
            'router_profile' => 'nullable|string|max:255',
            'router_nas' => 'nullable|string|max:255',
            'activation_date' => 'nullable|date',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'package_id' => $request->package_id,
            'tanggal_tagihan' => $request->tanggal_tagihan,
            'status' => $request->status, 
            'router_user' => $request->router_user,
            'router_password' => $request->router_password,
            'router_profile' => $request->router_profile,
            'router_nas' => $request->router_nas,
            'activation_date' => $request->activation_date,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        $customer->update($updateData);

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

        $file = $request->file('file_excel');
        
        $berhasil = 0;
        $duplikat = 0;
        $namaDuplikat = [];

        $type = $file->getClientOriginalExtension();
        $rows = \Spatie\SimpleExcel\SimpleExcelReader::create($file->getRealPath(), $type)->getRows();

        $rows->each(function(array $row) use (&$berhasil, &$duplikat, &$namaDuplikat) {
            // Bersihkan nama kolom jika ada whitespace
            $row = array_combine(
                array_map('trim', array_keys($row)),
                array_values($row)
            );

            $router_user = $row['user'] ?? null;
            $router_password = $row['password'] ?? null;
            $router_profile = $row['profile'] ?? null;
            $router_nas = $row['nas'] ?? null;
            $tgl_aktivasi = $row['Tanggal Aktivasi'] ?? null;
            $tgl_bayar = $row['Tanggal Pembayaran'] ?? null;
            $name = $row['name'] ?? null;
            $phone = $row['phone'] ?? null;
            $address = $row['address'] ?? null;

            // Jika baris kosong (tidak ada nama & user)
            if (empty($name) && empty($router_user)) {
                return;
            }

            // Generate email dummy karena wajib di tabel users
            $cleanName = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($name));
            $email = $router_user ? $router_user . '@client.com' : $cleanName . rand(100, 999) . '@client.com';
            
            // Format Tanggal Aktivasi
            if ($tgl_aktivasi instanceof \DateTimeInterface) {
                $tgl_aktivasi = $tgl_aktivasi->format('Y-m-d');
            } elseif (is_string($tgl_aktivasi) && !empty(trim($tgl_aktivasi))) {
                try {
                    // Jika formatnya pakai slash (02/08/2026) -> ubah ke dash (02-08-2026) agar dikenali sebagai d-m-Y oleh PHP
                    $tgl_aktivasi = \Carbon\Carbon::parse(str_replace('/', '-', $tgl_aktivasi))->format('Y-m-d');
                } catch (\Exception $e) {
                    $tgl_aktivasi = null; // Abaikan jika gagal parsing formatnya
                }
            } else {
                $tgl_aktivasi = null;
            }

            // Format Tanggal Pembayaran (bisa jadi DateTime atau String/Float dari Excel)
            $tagihan_val = null;
            if (is_numeric($tgl_bayar)) {
                $tagihan_val = (int)$tgl_bayar;
            } elseif ($tgl_bayar instanceof \DateTimeInterface) {
                $tagihan_val = (int)$tgl_bayar->format('d'); // Ambil tanggalnya saja
            } elseif (is_string($tgl_bayar)) {
                $tgl_bayar = trim($tgl_bayar);
                if (is_numeric($tgl_bayar)) {
                    $tagihan_val = (int)$tgl_bayar;
                } else {
                    try {
                        $parsedDate = \Carbon\Carbon::parse(str_replace('/', '-', $tgl_bayar));
                        $tagihan_val = (int)$parsedDate->format('d');
                    } catch (\Exception $e) {
                        $tagihan_val = null;
                    }
                }
            }

            // Cek duplikat berdasarkan router_user atau name
            $existingUser = null;
            if ($router_user) {
                $existingUser = User::where('router_user', $router_user)->first();
            }
            if (!$existingUser && $name) {
                $existingUser = User::where('name', $name)->first();
            }
            
            if ($existingUser) {
                $duplikat++;
                $namaDuplikat[] = $name ?: $router_user;
                return;
            }

            User::create([
                'name' => $name ?: 'No Name',
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'router_user' => $router_user,
                'router_password' => $router_password,
                'router_profile' => $router_profile,
                'router_nas' => $router_nas,
                'activation_date' => $tgl_aktivasi,
                'tanggal_tagihan' => $tagihan_val,
                'password' => bcrypt('12345678'), // Default password aplikasi
                'role' => 'client',
                'status' => 'aktif',
            ]);

            $berhasil++;
        });

        $pesan = "Import Selesai! {$berhasil} data berhasil ditambahkan.";
        
        if ($duplikat > 0) {
            $namaDouble = implode(', ', array_slice($namaDuplikat, 0, 5));
            $more = count($namaDuplikat) > 5 ? ' dan ' . (count($namaDuplikat) - 5) . ' lainnya' : '';
            $pesan .= " Namun, ada {$duplikat} data yang dilewati karena duplikat: {$namaDouble}{$more}.";
        }

        return back()->with('success', $pesan);
    }
}