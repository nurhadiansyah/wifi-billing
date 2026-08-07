<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'address',
        'status',
        'tanggal_tagihan',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // Relasi ke tabel Package (yang sudah Anda tambahkan sebelumnya)
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    // TAMBAHKAN KODE INI: Relasi ke tabel Invoice (Tagihan)
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'user_id');
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }
}
