<?php

namespace App\Http\Controllers\Keranjang;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function renderJson()
    {
        $data = Keranjang::with('Produk')->where('kasir_id', Auth::user()->id)->where('status', 'menunggu');
        return $this->resJson(false, ['total' => $data->count(), 'keranjang' => $data->get()], 200);
    }
}
