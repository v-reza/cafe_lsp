<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Riwayat extends Model
{
    use HasFactory;
    protected $table = 'riwayat';
    protected $fillable = [
        'kasir_id',
        'transaksi_id',
    ];

    /**
     * Get all of the Transaksi for the Riwayat
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function Transaksi()
    {
        return $this->hasOne(Transaksi::class, 'id', 'transaksi_id');
    }
}
