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
                    ] as $i => $b)
                        <option value="{{ $i }}" @selected(request('bulan') == $i)>
                            {{ $b }}
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

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-semibold">
                Filter
            </button>
        </form>
    </div>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-3 gap-6">
        <div class="bg-green-50 border border-green-200 p-5 rounded-xl">
            <div class="text-sm text-green-600">Total Penjualan</div>
            <div class="text-2xl font-bold text-green-700">
                Rp {{ number_format($totalPenjualan) }}
            </div>
        </div>

        <div class="bg-red-50 border border-red-200 p-5 rounded-xl">
            <div class="text-sm text-red-600">Total Pengeluaran</div>
            <div class="text-2xl font-bold text-red-700">
                Rp {{ number_format($totalPengeluaran) }}
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 p-5 rounded-xl">
            <div class="text-sm text-blue-600">Pemasukan Bersih</div>
            <div class="text-2xl font-bold text-blue-700">
                Rp {{ number_format($pemasukan) }}
            </div>
        </div>
    </div>

    {{-- GRAFIK --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="font-semibold mb-4">
            Grafik
            @if(request('tanggal') && request('bulan'))
                Penjualan per Jam
            @elseif(request('bulan'))
                Penjualan Harian
            @else
                Penjualan Bulanan
            @endif
        </h2>


        <div class="relative h-[480px]">
            <canvas id="grafikPemasukan"></canvas>
        </div>
    </div>


</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const grafikPenjualan = @json($grafikPenjualan);
    const grafikPengeluaran = @json($grafikPengeluaran);

    const isQty = grafikPenjualan.type === 'qty';

    new Chart(document.getElementById('grafikPemasukan'), {
        type: 'line',
        data: {
            labels: grafikPenjualan.labels,
            datasets: [
                {
                    label: isQty ? 'Jumlah Terjual' : 'Penjualan',
                    data: grafikPenjualan.data,
                    borderWidth: 2,
                    tension: 0.4,
                },
                ...(grafikPengeluaran ? [{
                    label: 'Pengeluaran',
                    data: grafikPengeluaran.data,
                    borderWidth: 2,
                    tension: 0.4,
                }] : [])
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true, // 🔥 ini bikin gak kebesaran
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => {
                            if (isQty) {
                                return value; // QTY
                            }
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection
