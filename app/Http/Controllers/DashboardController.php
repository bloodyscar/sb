<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;

class DashboardController extends Controller
{

    public function index()
    {
        $barang = Barang::count();
        $barangMasuk = BarangMasuk::count();
        $barangKeluar = BarangKeluar::count();
        return view('dashboard', compact('barang', 'barangMasuk', 'barangKeluar'));
    }

}
