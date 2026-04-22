<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    public $timestamps = false;

    protected $fillable = ['kategori'];
    
    public function produk()
    {
        return $this->hasMany(
            Produk::class,
            'id_kategori',     // FK di tabel produk
            'id_kategori'      // PK di tabel kategori
        );
    }
}


