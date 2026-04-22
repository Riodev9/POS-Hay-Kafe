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

            <button class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg text-sm font-semibold">
                Filter
            </button>
        </form>
    </div>

    {{-- TOTAL --}}
    <div class="bg-red-50 border border-red-200 p-4 rounded-xl">
        <div class="text-sm text-red-600">Total Pengeluaran</div>
        <div class="text-2xl font-bold text-red-700">
            Rp {{ number_format($total) }}
        </div>
    </div>

    {{-- FORM INPUT --}}
    <div class="bg-white shadow rounded-xl p-6">
        <h2 class="font-semibold mb-4">Tambah Pengeluaran</h2>

        <form id="formPengeluaran" class="grid grid-cols-4 gap-4">
            @csrf

            <input type="text"
                name="pengeluaran"
                placeholder="Nama pengeluaran"
                class="border rounded-lg px-3 py-2 col-span-1"
                required>

            <input type="number"
                name="total_pengeluaran"
                placeholder="Total"
                class="border rounded-lg px-3 py-2 col-span-1"
                required>

            <input type="text"
                name="catatan"
                placeholder="Catatan (opsional)"
                class="border rounded-lg px-3 py-2 col-span-2">

            <button
                type="submit"
                class="bg-red-600 h-10 text-white px-6 rounded-lg hover:bg-red-700">
                Simpan
            </button>
        </form>
    </div>

    {{-- TABEL --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal Operasional</th>
                    <th class="px-4 py-3 text-left">Pengeluaran</th>
                    <th class="px-4 py-3 text-left">Catatan</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($pengeluaran as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            {{ $p->tanggal_operasional }}
                        </td>
                        <td class="px-4 py-3 font-medium">
                            {{ $p->pengeluaran }}
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $p->catatan ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-right text-red-600 font-semibold">
                            Rp {{ number_format($p->total_pengeluaran) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <form method="POST"
                                action="{{ route('admin.pengeluaran.destroy', $p->id_pengeluaran) }}"
                                onsubmit="return confirm('Hapus pengeluaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500">
                            Tidak ada data pengeluaran
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- AJAX --}}
<script>
document.getElementById('formPengeluaran').addEventListener('submit', async function(e) {
    e.preventDefault()

    const form = e.target
    const data = new FormData(form)

    const res = await fetch("{{ route('admin.pengeluaran.store') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: data
    })

    const json = await res.json()

    if (json.success) {
        alert(json.message)
        location.reload()
    } else {
        alert(json.message)
        console.error(json.error)
    }
})
</script>
@endsection
