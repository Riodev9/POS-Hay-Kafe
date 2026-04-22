<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemasukanController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->tanggal;
        $bulan   = $request->bulan;
        $tahun   = $request->tahun ?? now()->year;

        /* =====================
         * TOTAL SUMMARY
         * ===================== */
        $totalPenjualan = Transaksi::query()
            ->when($tanggal, fn ($q) => $q->whereDay('tanggal_operasional', $tanggal))
            ->when($bulan, fn ($q) => $q->whereMonth('tanggal_operasional', $bulan))
            ->whereYear('tanggal_operasional', $tahun)
            ->sum('total');

        $totalPengeluaran = Pengeluaran::query()
            ->when($tanggal, fn ($q) => $q->whereDay('tanggal_operasional', $tanggal))
            ->when($bulan, fn ($q) => $q->whereMonth('tanggal_operasional', $bulan))
            ->whereYear('tanggal_operasional', $tahun)
            ->sum('total_pengeluaran');

        $pemasukan = $totalPenjualan - $totalPengeluaran;

        /* =====================
         * GRAFIK DATA
         * ===================== */

        // =====================
        // MODE 1: PER JAM (QTY)
        // =====================
        if ($tanggal && $bulan) {

            $labels = range(0, 23);
            $dataPenjualan = array_fill(0, 24, 0);

            $rows = DB::table('detail_transaksi')
                ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id')
                ->selectRaw('HOUR(transaksi.created_at) as jam, SUM(detail_transaksi.quantity) as total')
                ->whereDay('transaksi.tanggal_operasional', $tanggal)
                ->whereMonth('transaksi.tanggal_operasional', $bulan)
                ->whereYear('transaksi.tanggal_operasional', $tahun)
                ->groupBy('jam')
                ->get();

            foreach ($rows as $row) {
                $dataPenjualan[$row->jam] = $row->total;
            }

            $grafikPenjualan = [
                'labels' => $labels,
                'data'   => $dataPenjualan,
                'type'   => 'qty'
            ];

            $grafikPengeluaran = null;
        }

        // =====================
        // MODE 2: PER TANGGAL (RP)
        // =====================
        elseif ($bulan) {

            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            $labels = range(1, $daysInMonth);

            $penjualan = array_fill(0, $daysInMonth, 0);
            $pengeluaran = array_fill(0, $daysInMonth, 0);

            $rowsPenjualan = Transaksi::selectRaw(
                    'DAY(tanggal_operasional) as hari, SUM(total) as total'
                )
                ->whereMonth('tanggal_operasional', $bulan)
                ->whereYear('tanggal_operasional', $tahun)
                ->groupBy('hari')
                ->get();

            foreach ($rowsPenjualan as $row) {
                $penjualan[$row->hari - 1] = $row->total;
            }

            $rowsPengeluaran = Pengeluaran::selectRaw(
                    'DAY(tanggal_operasional) as hari, SUM(total_pengeluaran) as total'
                )
                ->whereMonth('tanggal_operasional', $bulan)
                ->whereYear('tanggal_operasional', $tahun)
                ->groupBy('hari')
                ->get();

            foreach ($rowsPengeluaran as $row) {
                $pengeluaran[$row->hari - 1] = $row->total;
            }

            $grafikPenjualan = [
                'labels' => $labels,
                'data'   => $penjualan,
                'type'   => 'currency'
            ];

            $grafikPengeluaran = [
                'labels' => $labels,
                'data'   => $pengeluaran
            ];
        }

        // =====================
        // MODE 3: PER BULAN (RP)
        // =====================
        else {

            $labels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            $penjualan = array_fill(0, 12, 0);
            $pengeluaran = array_fill(0, 12, 0);

            $rowsPenjualan = Transaksi::selectRaw(
                    'MONTH(tanggal_operasional) as bulan, SUM(total) as total'
                )
                ->whereYear('tanggal_operasional', $tahun)
                ->groupBy('bulan')
                ->get();

            foreach ($rowsPenjualan as $row) {
                $penjualan[$row->bulan - 1] = $row->total;
            }

            $rowsPengeluaran = Pengeluaran::selectRaw(
                    'MONTH(tanggal_operasional) as bulan, SUM(total_pengeluaran) as total'
                )
                ->whereYear('tanggal_operasional', $tahun)
                ->groupBy('bulan')
                ->get();

            foreach ($rowsPengeluaran as $row) {
                $pengeluaran[$row->bulan - 1] = $row->total;
            }

            $grafikPenjualan = [
                'labels' => $labels,
                'data'   => $penjualan,
                'type'   => 'currency'
            ];

            $grafikPengeluaran = [
                'labels' => $labels,
                'data'   => $pengeluaran
            ];
        }

        return view('admin.pemasukan', compact(
            'totalPenjualan',
            'totalPengeluaran',
            'pemasukan',
            'grafikPenjualan',
            'grafikPengeluaran'
        ));
    }
}
