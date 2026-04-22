<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Produk paling laku hari ini
        $produkTerlaris = DB::table('detail_transaksi')
            ->join('produk', 'produk.id_produk', '=', 'detail_transaksi.id_produk')
            ->join('transaksi', 'transaksi.id', '=', 'detail_transaksi.id_transaksi')
            ->whereDate('transaksi.tanggal_operasional', today())
            ->select(
                'produk.nama_produk',
                DB::raw('SUM(detail_transaksi.quantity) as total_terjual')
            )
            ->groupBy('produk.nama_produk')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        // 5 transaksi terakhir hari ini
        $transaksiTerakhir = DB::table('transaksi')
            ->join('users', 'users.id', '=', 'transaksi.user_id')
            ->whereDate('transaksi.tanggal_operasional', today())
            ->select(
                'transaksi.id',
                'transaksi.total',
                'transaksi.created_at',
                'users.name as kasir'
            )
            ->orderByDesc('transaksi.created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'produkTerlaris',
            'transaksiTerakhir'
        ));
    }
}
