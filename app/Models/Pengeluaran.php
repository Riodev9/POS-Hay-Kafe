<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = 'pengeluaran';
    protected $primaryKey = 'id_pengeluaran';

    protected $fillable = [
        'user_id',
        'tanggal_operasional',
        'pengeluaran',
        'catatan',
        'total_pengeluaran',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

