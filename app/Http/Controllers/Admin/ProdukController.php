<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $kategoriId = $request->get('kategori');

        $produk = Produk::with('kategori')
            ->when($kategoriId, function ($q) use ($kategoriId) {
                $q->where('id_kategori', $kategoriId);
            })
            ->orderBy('nama_produk')
            ->get();

        $kategori = Kategori::orderBy('kategori')->get();

        return view('admin.produk', compact('produk', 'kategori', 'kategoriId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_produk' => 'required|string',
            'harga' => 'required|numeric',
            'id_kategori' => 'required',
            'foto' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')
                ->store('produk', 'public');
        }

        Produk::create($data);


        Produk::create($request->all());

        return back()->with('success', 'Produk ditambahkan');
    }

    public function update(Request $request, Produk $produk)
    {
        $data = $request->validate([
            'nama_produk' => 'required|string',
            'harga' => 'required|numeric',
            'id_kategori' => 'required',
            'foto' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            // hapus foto lama (opsional tapi rapi)
            if ($produk->foto) {
                Storage::disk('public')->delete($produk->foto);
            }

            $data['foto'] = $request->file('foto')
                ->store('produk', 'public');
        }

        $produk->update($data);

        return back()->with('success', 'Produk berhasil diupdate');
    }


    public function destroy(Produk $produk)
    {
        $produk->delete();
        return back();
    }
}

