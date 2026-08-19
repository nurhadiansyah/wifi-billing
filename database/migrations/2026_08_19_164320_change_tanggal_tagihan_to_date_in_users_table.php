<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah kolom sementara untuk tanggal
        Schema::table('users', function (Blueprint $table) {
            $table->date('tanggal_tagihan_temp')->nullable()->after('tanggal_tagihan');
        });

        // 2. Pindahkan data dari integer ke date
        $users = DB::table('users')->whereNotNull('tanggal_tagihan')->get();
        
        $today = Carbon::today();
        foreach ($users as $user) {
            $tgl = (int) $user->tanggal_tagihan;
            if ($tgl >= 1 && $tgl <= 31) {
                // Set the date for this month, capping the day if it exceeds the month's days
                $safeDay = min($tgl, $today->daysInMonth);
                $dateStr = $today->copy()->setDay($safeDay)->format('Y-m-d');
                
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['tanggal_tagihan_temp' => $dateStr]);
            }
        }

        // 3. Hapus kolom lama
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tanggal_tagihan');
        });

        // 4. Rename kolom sementara jadi nama asli
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('tanggal_tagihan_temp', 'tanggal_tagihan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('tanggal_tagihan_temp')->nullable()->after('tanggal_tagihan');
        });

        $users = DB::table('users')->whereNotNull('tanggal_tagihan')->get();
        foreach ($users as $user) {
            $date = $user->tanggal_tagihan;
            if ($date) {
                $day = Carbon::parse($date)->day;
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['tanggal_tagihan_temp' => $day]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tanggal_tagihan');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('tanggal_tagihan_temp', 'tanggal_tagihan');
        });
    }
};
