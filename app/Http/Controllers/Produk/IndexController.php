<?php

namespace App\Http\Controllers\Produk;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Pembayaran;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function renderJson()
    {
        $produk = Produk::all();

        return $this->resJson(false, ['data' => $produk], 200);
    }

    public function addToCart(Request $request)
    {
        $authKasir = User::findOrFail(Auth::user()->id);

        $cekKeranjang = Keranjang::where('produk_id', $request->produk_id)
            ->where('kasir_id', $authKasir->id)->where('status', 'menunggu');

        if (!is_null($cekKeranjang->first())) {
            $cekKeranjang->update([
                'stock' => $cekKeranjang->first()->stock + 1
            ]);

            $getProduk = Produk::find($cekKeranjang->first()->produk_id);
            $totalPembayaran = $getProduk->harga * 1;

        } else {
            $keranjang = Keranjang::create([
                'produk_id' => $request->produk_id,
                'stock' => 1,
                'kasir_id' => $authKasir->id
            ]);

            $getProduk = Produk::find($keranjang->produk_id);
            $totalPembayaran = $getProduk->harga * $keranjang->stock;
        }

        $cekByrKasir = Pembayaran::where('kasir_id', $authKasir->id);
        if (is_null($cekByrKasir->first())) {
            Pembayaran::create([
                'kasir_id' => $authKasir->id,
                'total_pembayaran' => $totalPembayaran
            ]);
        } else {
            $cekByrKasir->update([
                'total_pembayaran' => $cekByrKasir->first()->total_pembayaran + $totalPembayaran
            ]);
        }
        return $this->resJson(false, ['message' => 'Berhasil menambahkan ke keranjang'], 200);

    }

    public function deleteCart(Request $request)
    {
        $authKasir = User::findOrFail(Auth::user()->id);

        $cekKeranjang = Keranjang::where('id', $request->keranjang_id)
            ->where('kasir_id', $authKasir->id);

        $getProduk = Produk::find($cekKeranjang->first()->produk_id);
        $totalPembayaran = $getProduk->harga * $cekKeranjang->first()->stock;

        $cekByrKasir = Pembayaran::where('kasir_id', $authKasir->id);
        if (!is_null($cekByrKasir->first())) {
            $cekByrKasir->update([
                'total_pembayaran' => $cekByrKasir->first()->total_pembayaran - $totalPembayaran
            ]);
        }

        $cekKeranjang->delete();

        return $this->resJson(false, ['message' => 'Berhasil hapus pesanan'], 200);
    }

    public function searchProduk($keywordSearch)
    {
        $search = Produk::where('nama', 'like', '%' . $keywordSearch . '%')->get();
        return $this->resJson(false, ['data' => $search], 200);
    }

    public function filterKategori($keyKategori)
    {
        $filter = Produk::where('kategori', $keyKategori)->get();
        return $this->resJson(false, ['data' => $filter], 200);
    }
}
