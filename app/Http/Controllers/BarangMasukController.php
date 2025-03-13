<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangMasuk = DB::select('SELECT bm.id, bm.barang_id, bm.tanggal, b.kode_barang, b.nama_barang, bm.stok, bm.deskripsi FROM barang_masuks bm INNER JOIN barangs b ON b.id = bm.barang_id');
        $barang = Barang::all();
        return view('pages.barangmasuk', compact('barangMasuk', 'barang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'stok' => 'required|numeric|min:1',  // Ensure stok is a number and greater than or equal to 0
            'tanggal' => 'required|date',  //
        ]);
        
        $request->merge([
            'barang_id' => $request->kode_barang,
            'deskripsi' => $request->deskripsi,
        ]);

        $barang = DB::select('SELECT * FROM barangs WHERE id = ?', [$request->kode_barang]);
        
        $newStok = $barang[0]->stok + $request->stok;

        // Update the stock in the database
        $updateStok = DB::update('UPDATE barangs SET stok = ? WHERE id = ?', [$newStok, $request->kode_barang]);

        // Check if the update was successful and handle accordingly
        if ($updateStok) {
            $barangMasuk = BarangMasuk::create($request->all());
            return redirect()->back()->with('success', 'Barang berhasil ditambahkan!');
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan.');
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'stok' => 'required|numeric|min:1',  // Ensure stok is a number and greater than or equal to 0
        ]);

        $barang = DB::select('SELECT * FROM barangs WHERE kode_barang = ?', [$request->kode_barang]);
        $barangMasuk = DB::select('SELECT * FROM barang_masuks WHERE id = ?', [$request->id]);

        $newStok = $barang[0]->stok - $barangMasuk[0]->stok;

        // Update the stock in the database
        $updateStok = DB::update('UPDATE barangs SET stok = ? WHERE kode_barang = ?', [$newStok, $request->kode_barang]);

        $newStokMasuk = $request->stok;

        $updateStokMasuk = DB::update('UPDATE barang_masuks SET stok = ?, deskripsi = ? WHERE id = ?', [$newStokMasuk, $request->deskripsi, $request->id]);
        

        $barang = DB::select('SELECT * FROM barangs WHERE kode_barang = ?', [$request->kode_barang]);
        $barangMasuk = DB::select('SELECT * FROM barang_masuks WHERE id = ?', [$request->id]);

        $newStok = $barang[0]->stok + $barangMasuk[0]->stok;

        // Update the stock in the database
        $updateStok = DB::update('UPDATE barangs SET stok = ? WHERE kode_barang = ?', [$newStok, $request->kode_barang]);

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barangMasuk = DB::select('SELECT * FROM barang_masuks WHERE id = ?', [$id]);
        $barang = DB::select('SELECT * FROM barangs WHERE id = ?', [$barangMasuk[0]->barang_id]);

        $newStok = $barang[0]->stok - $barangMasuk[0]->stok ;

        // Update the stock in the database
        $updateStok = DB::update('UPDATE barangs SET stok = ? WHERE id = ?', [$newStok, $barangMasuk[0]->barang_id]);

        $barang = DB::select('DELETE FROM barang_masuks WHERE id = ?', [$id]);
        return response()->json(['success' => true]);
    }
}
