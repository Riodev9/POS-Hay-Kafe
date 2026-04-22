<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::withCount('produk')->get();
        return view('admin.kategori', compact('kategori'));
    } 

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|max:100'
        ]);

        Kategori::create([
            'kategori' => $request->kategori
        ]);

        return redirect()
            ->route('admin.kategori')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'kategori' => 'required|string|max:100'
        ]);

        $kategori->update([
            'kategori' => $request->kategori
        ]);

        return back()->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->produk()->count() > 0) {
            return back()->with('error', 'Kategori masih memiliki produk');
        }

        $kategori->delete();

        return back()->with('success', 'Kategori dihapus');
    }
}
