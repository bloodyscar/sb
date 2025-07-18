<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangKeluar = DB::select('SELECT bm.id, bm.barang_id, bm.tanggal, b.kode_barang, b.nama_barang, b.harga, bm.stok, bm.deskripsi FROM barang_keluars bm INNER JOIN barangs b ON b.id = bm.barang_id');
        $barang = Barang::all();
        return view('pages.barangkeluar', compact('barangKeluar', 'barang'));
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
            'stok' => 'required|numeric|min:1',  
        ]);
        
        $request->merge([
            'tanggal' => $request->tanggal,  
            'barang_id' => $request->kode_barang,
        ]);
        
        // Ambil stok berdasarkan produk dengan FIFO
        $barangMasuks = BarangMasuk::where('barang_id',  $request->kode_barang)
                        ->where('stok', '>', 0)
                        ->orderBy('tanggal', 'asc')
                        ->get();
        
        $remaining = $request->stok;
        
        foreach ($barangMasuks as $item) {
            if ($item->stok >= $remaining) {
                $item->stok -= $remaining;
                $item->save();
        
                // Hapus otomatis jika stok habis
                if ($item->stok == 0) {
                    $item->delete();
                }
        
                $remaining = 0; // Keluar dari loop
                break;
            } else {
                $remaining -= $item->stok;
                $item->stok = 0;
                $item->save();
        
                // Hapus otomatis jika stok habis
                $item->delete();
            }
        }
        
        // Cek stok mencukupi
        if ($remaining > 0) {
            return response()->json(['message' => 'Stok tidak mencukupi'], 400);
        }
        
        $barang = DB::select('SELECT * FROM barangs WHERE id = ?', [$request->kode_barang]);
        if ($barang[0]->stok < $request->stok) {
            return response()->json(['error' => 'Stok barang tidak mencukupi.'], 400); 
        }
        
        $newStok = $barang[0]->stok - $request->stok;
        
        // Update stok di tabel barangs
        $updateStok = DB::update('UPDATE barangs SET stok = ? WHERE id = ?', [$newStok, $request->kode_barang]);
        
        if ($updateStok) {
            BarangKeluar::create($request->all());
            return redirect()->back()->with('success', 'Barang Keluar berhasil ditambahkan!');
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
            'stok' => 'required|numeric|min:0',  // Ensure stok is a number and greater than or equal to 0
        ]);

        $barang = DB::select('SELECT * FROM barangs WHERE kode_barang = ?', [$request->kode_barang]);
        $barangKeluar = DB::select('SELECT * FROM barang_keluars WHERE barang_id = ?', [$barang[0]->id]);

        $newStok = $barang[0]->stok + $barangKeluar[0]->stok;

        // Update the stock in the database
        $updateStok = DB::update('UPDATE barangs SET stok = ? WHERE kode_barang = ?', [$newStok, $request->kode_barang]);


        $newStokMasuk = $request->stok;
        $updateStokMasuk = DB::update('UPDATE barang_keluars SET stok = ?, deskripsi = ? WHERE id = ?', [$newStokMasuk, $request->deskripsi, $barangKeluar[0]->id]);
        

        $barang = DB::select('SELECT * FROM barangs WHERE kode_barang = ?', [$request->kode_barang]);
        $barangKeluar = DB::select('SELECT * FROM barang_keluars WHERE id = ?', [$barangKeluar[0]->id]);

        $newStok = $barang[0]->stok - $barangKeluar[0]->stok;

        // Update the stock in the database
        $updateStok = DB::update('UPDATE barangs SET stok = ? WHERE kode_barang = ?', [$newStok, $request->kode_barang]);

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $barangKeluar = DB::select('SELECT * FROM barang_keluars WHERE id = ?', [$id]);
        // $barang = DB::select('SELECT * FROM barangs WHERE id = ?', [$barangKeluar[0]->barang_id]);


        // $newStok = $barang[0]->stok + $barangKeluar[0]->stok;

        // // Update the stock in the database
        // $updateStok = DB::update('UPDATE barangs SET stok = ? WHERE id = ?', [$newStok, $barangKeluar[0]->barang_id]);

        // $barangKeluar = DB::select('DELETE FROM barang_keluars WHERE id = ?', [$id]);
        // return response()->json(['success' => true]);

        try {
            $barangKeluar = BarangKeluar::find($id);


            if (!$barangKeluar) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            $sisa = $barangKeluar->stok;
            $barangMasuks = BarangMasuk::where('barang_id', $barangKeluar->barang_id)
                ->orderBy('created_at', 'asc')
                ->get();



            foreach ($barangMasuks as $item) {
                if ($sisa <= 0) {
                    break;
                }

                // Tambahkan stok kembali
                $stokTambah = min($item->stok, $sisa);
                $item->stok += $stokTambah;
                $item->save();

                $sisa -= $stokTambah;
            }

            $barangKeluar->delete();

            return response()->json(['message' => 'Barang keluar berhasil dihapus dan stok telah dikembalikan']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan.'], 400);
        }
        
    }
}
