<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Meja;
use App\Models\Pembayaran;
use App\Models\Produk;
use App\Models\Riwayat;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function addTransaksi(Request $request)
    {
        $pembayaranKeranjang = Pembayaran::where('kasir_id', Auth::user()->id);

        if ($request->tunai < $pembayaranKeranjang->first()->total_pembayaran) {
            return $this->resJson(true, 'Uang tunai tidak cukup untuk melanjutkan pembayaran', 404);
        }

        $kembalian = $request->tunai - $pembayaranKeranjang->first()->total_pembayaran;

        $transaksi = Transaksi::create([
            'kasir_id' => Auth::user()->id,
            'total_pembayaran' => $pembayaranKeranjang->first()->total_pembayaran,
            'bayar' => $request->tunai,
            'kembalian' => $kembalian,
            'status' => 'berhasil'
        ]);

        $updateStatusKeranjang = Keranjang::where('status', 'menunggu')->where('kasir_id', Auth::user()->id);

        $pembayaranKeranjang->update([
            'total_pembayaran' => 0
        ]);

        Riwayat::create([
            'kasir_id' => Auth::user()->id,
            'transaksi_id' => $transaksi->id
        ]);

        Meja::where('meja', $request->meja)->update([
            'status' => 'dipakai'
        ]);

        $getStockKeranjang = $updateStatusKeranjang->get();

        foreach($getStockKeranjang as $itemKeranjang) {
            $stockKeranjang = $itemKeranjang->stock;

            $getProdukByKeranjang = Produk::find($itemKeranjang->produk_id);
            $getProdukByKeranjang->update([
                'stock' => $getProdukByKeranjang->stock - $stockKeranjang
            ]);

            $updateStatusKeranjang->update([
                'transaksi_id' => $transaksi->id
            ]);
        }

        $updateStatusKeranjang->update([
            'status' => 'berhasil'
        ]);

        return $this->resJson(false, ['message' => 'Berhasil melakukan pembayaran'], 200);
    }
}
