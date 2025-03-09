<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    protected $fillable = ['barang_id', 'stok',  'tanggal', 'deskripsi',];

    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class, 'barang_id');
    }
}
