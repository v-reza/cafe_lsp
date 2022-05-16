<?php

namespace App\Http\Controllers\Pembayaran;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Pembayaran;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function renderJson()
    {
        $data = Pembayaran::where('kasir_id', Auth::user()->id)->first();

        return $this->resJson(false, ['data' => $data], 200);
    }

    public function addPesanModal(Request $request)
    {
        $keranjang = Keranjang::where('id', $request->keranjangId);
        $keranjang->update([
            'stock' => $keranjang->first()->stock + 1
        ]);
        $getProdukHarga = Produk::where('id', $keranjang->first()->produk_id);
        $totalPembayaran = $getProdukHarga->first()->harga * 1;

        $cekByrKasir = Pembayaran::where('kasir_id', Auth::user()->id);
        $cekByrKasir->update([
            'total_pembayaran' => $cekByrKasir->first()->total_pembayaran + $totalPembayaran
        ]);

        return $this->resJson(false, ['message' => 'Berhasil menambahkan pesanan'], 200);
    }

    public function kurangPesanModal(Request $request)
    {
        $keranjang = Keranjang::where('id', $request->keranjangId);
        if ($keranjang->first()->stock == 1) {
            $getProdukHarga = Produk::where('id', $keranjang->first()->produk_id);
            $totalPembayaran = $getProdukHarga->first()->harga * 1;

            $cekByrKasir = Pembayaran::where('kasir_id', Auth::user()->id);
            $cekByrKasir->update([
                'total_pembayaran' => $cekByrKasir->first()->total_pembayaran - $totalPembayaran
            ]);

            $keranjang->delete();
            return $this->resJson(false, ['message' => 'Berhasil menghapus pesanan'], 200);
        } else {
            $keranjang->update([
                'stock' => $keranjang->first()->stock - 1
            ]);
            $getProdukHarga = Produk::where('id', $keranjang->first()->produk_id);
            $totalPembayaran = $getProdukHarga->first()->harga * 1;

            $cekByrKasir = Pembayaran::where('kasir_id', Auth::user()->id);
            $cekByrKasir->update([
                'total_pembayaran' => $cekByrKasir->first()->total_pembayaran - $totalPembayaran
            ]);

            return $this->resJson(false, ['message' => 'Berhasil mengurangi pesanan'], 200);
        }
    }
}
