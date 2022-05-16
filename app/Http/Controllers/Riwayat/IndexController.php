<?php

namespace App\Http\Controllers\Riwayat;

use App\Http\Controllers\Controller;
use App\Models\Riwayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function renderJson()
    {
        $riwayat = Riwayat::with(['Transaksi' => function ($q) {
            $q->with(['Keranjang' => function ($qK) {
                $qK->with('Produk')->where('status', 'berhasil');
            }]);
        }])->where('kasir_id', Auth::user()->id)->get();

        return $this->resJson(false, ['data' => $riwayat], 200);
    }
}
