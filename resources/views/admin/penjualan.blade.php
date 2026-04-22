@extends('layouts.adminlayout')

@section('content')

<div class="space-y-6">

    {{-- FILTER --}}
    <div class="bg-white/40 p-4 rounded-xl shadow">
        <form method="GET" class="flex flex-wrap gap-4 items-end">

            {{-- TANGGAL --}}
            <div>
                <label class="text-sm text-gray-500">Tanggal</label>
                <select name="tanggal" class="border rounded-lg px-3 py-2 text-sm w-24">
                    <option value="">Semua</option>
                    @for ($d = 1; $d <= 31; $d++)
                        <option value="{{ $d }}" @selected(request('tanggal') == $d)>
                            {{ $d }}
                        </option>
                    @endfor
                </select>
            </div>

            {{-- BULAN --}}
            <div>
                <label class="text-sm text-gray-500">Bulan</label>
                <select name="bulan" class="border rounded-lg px-3 py-2 text-sm w-28">
                    <option value="">Semua</option>
                    @foreach ([
                        1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',
                        7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'
                    ] as $num => $name)
                        <option value="{{ $num }}" @selected(request('bulan') == $num)>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- TAHUN --}}
            <div>
                <label class="text-sm text-gray-500">Tahun</label>
                <select name="tahun" class="border rounded-lg px-3 py-2 text-sm w-24">
                    @for ($y = now()->year; $y >= 2024; $y--)
                        <option value="{{ $y }}" @selected(request('tahun') == $y)>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>

            <button
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-semibold">
                Filter
            </button>
        </form>
    </div>


    {{-- TOTAL --}}
    <div class="bg-green-50 border border-green-200 p-4 rounded-xl">
        <div class="text-sm text-green-600">Total Penjualan</div>
        <div class="text-2xl font-bold text-green-700">
            Rp {{ number_format($total) }}
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left">Kode Transaksi</th>
                    <th class="px-4 py-3">Tanggal Operasional</th>
                    <th class="px-4 py-3">Metode</th>
                    <th class="px-4 py-3 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($transaksi as $t)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">
                            {{ $t->kode_transaksi }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            {{ $t->tanggal_operasional }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $t->metode_bayar === 'cash'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-blue-100 text-blue-700' }}">
                                {{ strtoupper($t->metode_bayar) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right font-semibold">
                            Rp {{ number_format($t->total) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-500">
                            Tidak ada data transaksi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
