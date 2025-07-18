<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Exports\BarangExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barang = Barang::all();
        return view('pages.barang', compact('barang'));
    }

    public function export()
    {
        return Excel::download(new BarangExport, 'barang_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required',
            'nama_barang' => 'required',
            'harga' => 'required|numeric',
        ]);
    
        $request->merge([
            'kode_barang' => strtoupper($request->kode_barang),
        ]);
        
        Barang::create($request->all());
        return redirect()->back()->with('success', 'Barang berhasil ditambahkan!');
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

            
            
            $barang = Barang::findOrFail($id);

            $request->validate([
                'kode_barang' => [
                    'required',
                    Rule::unique('barangs')->ignore($barang->kode_barang), 
                ],
                'nama_barang' => 'required|string|max:255',
            ]);
           
            
            $barang->update([
                'kode_barang'         => $request->kode_barang,
                'nama_barang'   => $request->nama_barang,
                'deskripsi'         => $request->deskripsi
            ]);

            return redirect()->back()->with('success', 'Barang berhasil diubah!');
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Barang::destroy($id);
        return response()->json(['success' => true]);
        
    }
}
