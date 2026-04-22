<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengeluaran::with('user');

        // FILTER TAHUN (BASE)
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

        $pengeluaran = $query
            ->orderByDesc('tanggal_operasional')
            ->get();

        $total = $pengeluaran->sum('total_pengeluaran');

        return view('admin.pengeluaran', compact(
            'pengeluaran',
            'total'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pengeluaran'       => 'required|string|max:255',
            'total_pengeluaran' => 'required|numeric|min:0',
            'catatan'           => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $data = Pengeluaran::create([
                'user_id'             => Auth::id(),
                'pengeluaran'         => $request->pengeluaran,
                'tanggal_operasional' => $this->tanggalOperasional(),
                'catatan'             => $request->catatan,
                'total_pengeluaran'   => $request->total_pengeluaran,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengeluaran berhasil disimpan',
                'data'    => $data
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pengeluaran',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        Pengeluaran::findOrFail($id)->delete();

        return redirect()->back()
            ->with('success', 'Pengeluaran berhasil dihapus');
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
}
