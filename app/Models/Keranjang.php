<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    protected $table = 'keranjang';
    protected $fillable = [
        'produk_id',
        'stock',
        'kasir_id',
        'status',
        'transaksi_id'
    ];

    /**
     * Get the user associated with the Keranjang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function Produk()
    {
        return $this->hasOne(Produk::class, 'id', 'produk_id');
    }
}
