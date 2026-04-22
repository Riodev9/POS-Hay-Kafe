@extends('layouts.adminlayout')

@section('content')
    <div class="mb-6">
        <div class="bg-white/30 backdrop-blur-xl
                    border border-white/30
                    rounded-2xl p-5
                    shadow-lg shadow-black/10">

            <h2 class="text-lg font-inter font-semibold mb-4 text-gray-800">
                Tambah Kategori
            </h2>

            <form action="{{ route('admin.kategori.store') }}"
                method="POST"
                class="flex flex-col md:flex-row gap-3">

                @csrf

                <input type="text"
                    name="kategori"
                    placeholder="kategori"
                    required
                    class="flex-1 rounded-xl px-4 py-3
                            bg-white/70 border border-gray-200
                            focus:outline-none focus:ring-2 focus:ring-blue-400">

                <button type="submit"
                        class="px-6 py-3 rounded-xl
                            bg-blue-600 text-white
                            hover:bg-blue-700 transition">
                    Simpan
                </button>
            </form>

        </div>
    </div>

    <div class="bg-white/30 backdrop-blur-xl
                border border-white/30
                rounded-2xl
                shadow-lg shadow-black/10
                overflow-hidden">

        <table class="w-full text-sm">
            <thead class="bg-white/50">
                <tr class="text-left text-gray-700">
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3 text-center">Produk</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-white/30">
                @foreach ($kategori as $item)
                    <tr class="hover:bg-white/40 transition">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>

                        <!-- KATEGORI + INLINE EDIT -->
                        <td class="px-4 py-3"
                            x-data="{ edit:false, value:@js($item->kategori) }">

                            <!-- VIEW -->
                            <div x-show="!edit" class="flex items-center gap-2">
                                <span class="font-medium" x-text="value"></span>

                                <button @click="edit=true"
                                    class="text-blue-500 hover:underline text-xs">
                                    Edit
                                </button>
                            </div>

                            <!-- EDIT -->
                            <form x-show="edit"
                                method="POST"
                                action="{{ route('admin.kategori.update', $item) }}"
                                class="flex gap-2 items-center">
                                @csrf
                                @method('PUT')

                                <input type="text"
                                    name="kategori"
                                    x-model="value"
                                    class="border rounded-lg px-2 py-1 text-sm w-full"
                                    required>

                                <button class="text-green-600 text-sm">simpan</button>
                                <button type="button"
                                        @click="edit=false"
                                        class="text-gray-500 text-sm">batal</button>
                            </form>
                        </td>

                        <!-- JUMLAH PRODUK -->
                        <td class="px-4 py-3 text-center">
                            {{ $item->produk_count }}
                        </td>

                        <!-- AKSI DELETE -->
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('admin.kategori.destroy', $item) }}"
                                method="POST"
                                onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-500 text-xs hover:underline">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </div>
@endsection