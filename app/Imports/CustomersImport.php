<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow; 

class CustomersImport implements ToCollection, WithHeadingRow
{
    public $berhasil = 0;
    public $duplikat = 0;
    public $namaDuplikat = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $cekPelanggan = User::where('name', $row['nama'])
                                ->orWhere('email', $row['email'])
                                ->first();

            if ($cekPelanggan) {
                $this->duplikat++;
                $this->namaDuplikat[] = $row['nama'];
                continue; 
            }
            User::create([
                'name'            => $row['nama'],
                'email'           => $row['email'],
                'phone'           => $row['no_hp'],
                'address'         => $row['alamat'],
                'password'        => bcrypt('pelanggan123'), 
                'role'            => 'client',
                'status'          => 'aktif',
                'tanggal_tagihan' => $row['tanggal_tagihan'] ?? 1,
                'router_user'     => $row['user'],
                'router_password' => $row['password'],
                'router_profile'  => $row['profile'],
                'router_nas'      => $row['nas'], // <--- TAMBAHKAN INI
            ]);

            $this->berhasil++;
        }
    }
}