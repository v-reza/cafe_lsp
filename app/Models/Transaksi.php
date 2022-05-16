<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $fillable = [
        'kasir_id',
        'bayar',
        'total_pembayaran',
        'kembalian',
        'status'
    ];

    /**
     * Get all of the Keranjang for the Transaksi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Keranjang()
    {
        return $this->hasMany(Keranjang::class, 'transaksi_id', 'id');
    }
}
