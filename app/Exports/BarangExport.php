<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BarangExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Barang::select('id', 'kode_barang', 'nama_barang', 'deskripsi', 'stok')->get(); // Select only needed columns
    }

    public function headings(): array
    {
        return ["id", "Kode Barang", "Nama Barang", "Deskripsi", "Stok", ]; // Column headers
    }
}
