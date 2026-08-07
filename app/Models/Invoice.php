<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_number',
        'amount',
        'due_date',
        'status',
        
        'tripay_reference',
        'payment_method',
        'checkout_url',
        'instructions',
    ];

    // Relasi ke tabel User (Pelanggan)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}