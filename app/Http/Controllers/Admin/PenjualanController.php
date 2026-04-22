<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::query();

        // FILTER TAHUN (WAJIB JADI BASE)
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_operasional', $request->tahun);
        }

        // FILTER BULAN
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_operasional', $request->bulan);
        }

        // FILTER TANGGAL (HARI)
        if ($request->filled('tanggal')) {
            $query->whereDay('tanggal_operasional', $request->tanggal);
        }

        $transaksi = $query
            ->orderBy('tanggal_operasional', 'desc')
            ->get();

        $total = $transaksi->sum('total');

        return view('admin.penjualan', compact('transaksi', 'total'));
    }

}
