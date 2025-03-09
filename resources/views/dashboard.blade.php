@extends('layouts.master') 

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h3>Dashboard</h3>
<div class="order-1">
    <div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card">
        <div class="card-body">
            <span class="fw-semibold d-block mb-1">Total Barang</span>
            <h3 class="card-title mb-2">{{ $barang }}</h3>
        </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card">
        <div class="card-body">
            
            <span class="fw-semibold d-block mb-1">Total Barang Masuk</span>
            <h3 class="card-title mb-2">{{ $barangMasuk }}</h3>
        </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card">
        <div class="card-body">
            <span class="fw-semibold d-block mb-1">Total Barang Keluar</span>
            <h3 class="card-title mb-2">{{ $barangKeluar }}</h3>
        </div>
        </div>
    </div>
    
    </div>
</div>
</div>
@endsection
