@extends('layouts.adminlayout')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-3 gap-6">
        {{-- KIRI --}}
        <div class="space-y-6">

            {{-- PRODUK TERLARIS --}}
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-semibold mb-4">Produk Paling Laku Hari Ini</h3>

                <table class="w-full text-sm">
                    <tbody>
                    @forelse($produkTerlaris as $item)
                        <tr class="border-b last:border-none">
                            <td class="py-2">{{ $item->nama_produk }}</td>
                            <td class="py-2 text-right font-semibold">
                                {{ $item->total_terjual }} pcs
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-4 text-center text-gray-500">
                                Belum ada penjualan hari ini
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- KALENDER --}}
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-semibold mb-4">Kalender</h3>
                <input id="calendarDashboard" class="hidden">
            </div>

        </div>

        {{-- KANAN --}}
        <div class="col-span-2">
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-semibold mb-4">5 Transaksi Terakhir Hari Ini</h3>

                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-gray-500 border-b">
                            <th class="text-left py-2">Jam</th>
                            <th class="text-left py-2">Kasir</th>
                            <th class="text-right py-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($transaksiTerakhir as $trx)
                        <tr class="border-b last:border-none">
                            <td class="py-2">
                                {{ \Carbon\Carbon::parse($trx->created_at)->format('H:i') }}
                            </td>
                            <td class="py-2">{{ $trx->kasir }}</td>
                            <td class="py-2 text-right font-semibold">
                                Rp {{ number_format($trx->total) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500">
                                Belum ada transaksi hari ini
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- FLATPICKR --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
flatpickr("#calendarDashboard", {
    inline: true,
    defaultDate: "today"
});
</script>
@endsection
