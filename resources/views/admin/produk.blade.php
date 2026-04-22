@extends('layouts.adminlayout')

@section('content')

{{-- bagian tambah produk --}}
<div class="bg-white/40 backdrop-blur-xl rounded-2xl p-5 mb-6 border border-white/30">
    <form
        method="POST"
        action="{{ route('admin.produk.store') }}"
        enctype="multipart/form-data"
        class="grid grid-cols-1 md:grid-cols-5 gap-3"
    >
        @csrf

        <!-- Nama -->
        <input type="text" name="nama_produk"
            placeholder="Nama produk"
            class="rounded-xl px-4 py-3 bg-white/70 border"
            required>

        <!-- Harga -->
        <input type="number" name="harga"
            placeholder="Harga"
            class="rounded-xl px-4 py-3 bg-white/70 border"
            required>

        <!-- Kategori -->
        <select name="id_kategori"
            class="rounded-xl px-4 py-3 bg-white/70 border"
            required>
            <option value="">Pilih kategori</option>
            @foreach($kategori as $k)
                <option value="{{ $k->id_kategori }}">{{ $k->kategori }}</option>
            @endforeach
        </select>

        <!-- Foto -->
        <input type="file"
            name="foto"
            accept="image/*"
            class="rounded-xl px-4 py-3 bg-white/70 border text-sm">

        <!-- Submit -->
        <button
            class="bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
            Simpan
        </button>
    </form>
</div>


{{--bagian filter and tabel--}}
<div class="bg-white/40 backdrop-blur-xl rounded-2xl p-5 mb-6 border border-white/30">
    <form method="GET" class="mb-2 flex flex-wrap gap-3 items-center">
        <select name="kategori"
            onchange="this.form.submit()"
            class="rounded-xl px-4 py-2
                bg-white/40 backdrop-blur
                border w-36 border-white/30
                text-sm focus:outline-none">

            <option value="">Semua Kategori</option>

            @foreach($kategori as $k)
                <option value="{{ $k->id_kategori }}"
                    {{ $kategoriId == $k->id_kategori ? 'selected' : '' }}>
                    {{ $k->kategori }}
                </option>
            @endforeach
        </select>

    </form>
    <table class="bg-white/30 backdrop-blur-lg w-full text-sm rounded-2xl">
        <thead class="">
            <tr class="">
                <th class="px-4 py-3">No</th>
                <th class="px-4 py-3">Produk</th>
                <th class="px-4 py-3">Kategori</th>
                <th class="px-4 py-3 text-right">Harga</th>
                <th class="px-4 py-3 text-center">foto</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-white/30">
            @foreach($produk as $item)
            <tr
                x-data="{
                    edit: false,
                    nama: '{{ $item->nama_produk }}',
                    harga: '{{ $item->harga }}',
                    kategori: '{{ $item->id_kategori }}'
                }"
                class="hover:bg-white/40 text-center"
            >
                <td class="px-4 py-3">{{ $loop->iteration }}</td>

                <!-- NAMA PRODUK -->
                <td class="px-4 py-3 text-left">
                    <span x-show="!edit" x-text="nama"></span>

                    <input x-show="edit"
                        x-model="nama"
                        class="w-full rounded-lg px-2 py-1 text-sm
                            bg-white/70 border border-gray-200">
                </td>

                <!-- KATEGORI -->
                <td class="px-4 py-3">
                    <span x-show="!edit">
                        {{ $item->kategori->kategori }}
                    </span>

                    <select x-show="edit"
                        x-model="kategori"
                        class="rounded-lg px-2 py-1 text-sm
                            bg-white/70 border w-20 border-gray-200">
                        @foreach($kategori as $k)
                            <option value="{{ $k->id_kategori }}">
                                {{ $k->kategori }}
                            </option>
                        @endforeach
                    </select>
                </td>

                <!-- HARGA -->
                <td class="px-4 py-3 text-right">
                    <span x-show="!edit">
                        Rp {{ number_format($item->harga) }}
                    </span>

                    <input x-show="edit"
                        type="number"
                        x-model="harga"
                        class="w-full rounded-lg px-2 py-1 text-sm text-right
                            bg-white/70 border border-gray-200">
                </td>

                <!-- FOTO -->
                <td class="px-4 py-3">
                    <img
                        x-show="!edit"
                        src="{{ asset('storage/' . $item->foto) }}"
                        class="w-14 h-14 rounded-lg object-cover mx-auto">

                    <input
                        x-show="edit"
                        type="file"
                        name="foto"
                        class="text-xs"
                        form="form-{{ $item->id_produk }}">
                </td>


                <!-- AKSI -->
                <td class="px-4 py-3 flex justify-center gap-2">

                    <!-- VIEW MODE -->
                    <template x-if="!edit">
                        <button @click="edit=true"
                            class="text-blue-500 text-xs hover:underline">
                            Edit
                        </button>
                    </template>

                    <!-- EDIT MODE -->
                    <template x-if="edit">
                        <form
                            id="form-{{ $item->id_produk }}"
                            method="POST"
                            action="{{ route('admin.produk.update', $item) }}"
                            enctype="multipart/form-data"
                            class="flex gap-2 items-center"
                        >
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="nama_produk" :value="nama">
                            <input type="hidden" name="harga" :value="harga">
                            <input type="hidden" name="id_kategori" :value="kategori">

                            <button class="text-green-600 text-xs">Simpan</button>
                            <button type="button"
                                @click="edit=false"
                                class="text-gray-500 text-xs">Batal</button>
                        </form>

                    </template>

                    <!-- DELETE -->
                    <form method="POST"
                        action="{{ route('admin.produk.destroy', $item->id_produk) }}">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-500 text-xs">Hapus</button>
                    </form>
                </td>

            </tr>
            @endforeach
        </tbody>

    </table>
</div>

@endsection