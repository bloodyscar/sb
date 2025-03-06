@extends('layouts.master') 

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col-md-12">
      <div class="card mb-4">
        <h5 class="card-header">Daftar Stok</h5>
        <!-- Account -->
        <div class="card-body">
          <table id="myTable" class="display table table-striped">
            <thead>
              <tr>
                <th>Nama Barang</th>
                <th>Jumlah Stok</th>
                <th>Satuan</th>
                <th>Deskripsi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($barang as $item)
              <tr>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->stok }}</td>
                <td>{{ $item->satuan }}</td>
                <td>{{ $item->deskripsi ?? "-" }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>  
      </div>
    </div>
  </div>
</div>
@endsection
