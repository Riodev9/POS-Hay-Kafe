<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index()
    {
        $kategori = Kategori::orderBy('kategori')
            ->get()
            ->map(fn ($k) => [
                'id'   => $k->id_kategori,
                'nama' => $k->kategori,
            ]);

        $produk = Produk::with('kategori')
            ->orderBy('nama_produk')
            ->get()
            ->map(fn ($p) => [
                'id'          => $p->id_produk,
                'nama'        => $p->nama_produk,
                'harga'       => $p->harga,
                'stok'        => $p->stok,
                'id_kategori' => $p->id_kategori, // 🔴 penting buat filter
                'gambar'      => $p->foto
                    ? asset('storage/' . $p->foto)
                    : asset('images/no-image.png'),
            ]);

        return view('kasir.sale', compact('kategori', 'produk'));
    }

    private function tanggalOperasional(): string
    {
        $now = now();

        // cutoff jam 01:00
        if ($now->format('H:i') < '01:00') {
            return $now->subDay()->toDateString();
        }

        return $now->toDateString();
    }
    private function generateKodeTransaksi(string $tanggalOperasional): string
    {
        $last = Transaksi::where('tanggal_operasional', $tanggalOperasional)
            ->orderByDesc('id')
            ->first();

        $nextNumber = $last
            ? (int) substr($last->kode_transaksi, -4) + 1
            : 1;

        return sprintf(
            'TRX-%s-%04d',
            str_replace('-', '', $tanggalOperasional),
            $nextNumber
        );
    }

    public function store(Request $request)
    {
        sleep(1.5);
        DB::beginTransaction();

        try {
            $tanggalOperasional = $this->tanggalOperasional();

            $transaksi = Transaksi::create([
                'kode_transaksi'       => $this->generateKodeTransaksi($tanggalOperasional),
                'tanggal_operasional' => $tanggalOperasional,
                'tanggal'             => now(),
                'total'               => collect($request->cart)
                                            ->sum(fn ($i) => $i['qty'] * $i['harga']),
                'metode_bayar'        => $request->metode_bayar,
                'catatan'             => $request->catatan,
                'user_id'             => Auth::id(),
            ]);

            foreach ($request->cart as $item) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id,
                    'id_produk'    => $item['id'],
                    'quantity'          => $item['qty'],
                    'subtotal'        => $item['harga'],
                ]);

                Produk::where('id_produk', $item['id'])
                    ->decrement('stok', $item['qty']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil',
                'data'    => [
                    'kode_transaksi' => $transaksi->kode_transaksi,
                    'total'          => $transaksi->total,
                ]
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Transaksi gagal',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


}
