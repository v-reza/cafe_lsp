<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_keranjang';

    protected $fillable = [
        'kasir_id',
        'total_pembayaran',
    ];
}
