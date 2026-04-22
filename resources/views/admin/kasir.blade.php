@extends('layouts.adminlayout')

@section('content')
{{-- resources/views/admin/kasir/index.blade.php --}}
<div class="bg-white/30 rounded-2xl p-5">

    {{-- tambah kasir --}}
    <form method="POST" action="{{ route('admin.kasir.store') }}"
          class="grid grid-cols-4 gap-3 mb-6">
        @csrf
        <input type="text" name="name" placeholder="Nama" class="bg-white/50 input rounded-lg">
        <input type="email" name="email" placeholder="Email" class="bg-white/50 input rounded-lg">
        <input name="password" placeholder="Password" class="bg-white/50 input rounded-lg">
        <button class="bg-blue-600 h-12 text-white rounded-xl shadow-lg">Tambah</button>
    </form>

    {{-- tabel --}}
    <table class="w-full border-collapse text-sm">
        <thead class="">
            <tr>
                <td class="font-bold">Nama</td>
                <td>Email</td>
                <td>Aksi</td>
            </tr>
        </thead>
        <tbody class="border-collapse">
            @foreach($kasir as $k)
            <tr x-data="{ edit:false }">
                <td>
                    <input x-show="edit" x-model="$refs.nama.value"
                        name="name" class="input" value="{{ $k->name }}">
                    <span x-show="!edit">{{ $k->name }}</span>
                </td>

                <td>
                    <span>{{ $k->email }}</span>
                </td>

                <td class="flex gap-2">

                    <form method="POST"
                        action="{{ route('admin.kasir.destroy', $k) }}">
                        @csrf @method('DELETE')
                        <button class="text-red-600 font-bold">Hapus</button>
                    </form>
                </td>
            </tr>
        </tbody>
        @endforeach
    </table>
</div>

@endsection