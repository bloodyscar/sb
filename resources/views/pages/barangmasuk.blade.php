@extends('layouts.master') 

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">

    <div class="col-md-4">
      <h3 class="">Barang Masuk</h3>

              <!-- Button trigger modal -->
      <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
        + Tambah
      </button>
    </div>
    <div class="col-md-12">
      <div class="card mb-4">
        <!-- Account -->
        <div class="card-body">
          <table id="myTable" class="display table table-striped">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Stok Masuk</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($barangMasuk as $item)
              <tr>
                <td>{{ $item->tanggal ?? "-" }}</td>
                <td>{{ $item->kode_barang }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->stok }}</td>
                <td>{{ $item->deskripsi ?? "-" }}</td>
                <td>
                  <!-- <button class="btn btn-warning btn-sm editBarang" data-id="{{ $item->barang_id }}" data-nama="{{ $item->nama_barang }}" data-kode="{{ $item->kode_barang }}" data-stok="{{ $item->stok }}" data-deskripsi="{{ $item->deskripsi }}">
                    ✏️ Edit
                  </button> -->
                  <button class="btn btn-danger btn-sm deleteBarangMasuk" data-id="{{ $item->id }}">
                    🗑️ Delete
                  </button>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center">
                  <b>Data Kosong</b>
                </td>
              </tr>
              
              @endforelse
              
            </tbody>
          </table>
        </div>  
      </div>
    </div>
  </div>
</div>

{{-- Modal Edit  --}}
<div class="modal fade" id="exampleModalEdit" tabindex="-1" aria-labelledby="exampleModalEditLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalEditLabel">Update Barang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formBarangUpdate" method="POST">
          @csrf

          <div class="modal-body">
            <div class="mb-3">
              <label for="kode_barang" class="form-label">Kode Barang</label>
              <input type="text" class="form-control" name="kode_barang" placeholder="Masukkan kode barang" required style="text-transform: uppercase;">
            </div>
  
            <div class="mb-3">
              <label for="nama_barang" class="form-label">Nama Barang</label>
              <input type="text" class="form-control" name="nama_barang" placeholder="Masukkan nama barang" required>
            </div>
  
            <div class="mb-3">
              <label for="stok" class="form-label">Stok</label>
              <input type="number" class="form-control" name="stok" placeholder="Masukkan jumlah stok" required>
            </div>
  
            <div class="mb-3">
              <label for="deskripsi" class="form-label">Deskripsi</label>
              <textarea class="form-control" name="deskripsi" placeholder="Masukkan deskripsi barang"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
      
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Barang Masuk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formBarang">
          @csrf
          <div class="modal-body">

            <div class="mb-3">
              <label for="kode_barang" class="form-label">Kode Barang</label>
              <select name="kode_barang" class="form-select" required>
                <option value="">-- Pilih Kode Barang --</option>
                @foreach($barang as $b)
                  <option value="{{ $b->id }}">{{ $b->kode_barang }} - {{ $b->nama_barang }}</option>
                @endforeach
              </select>
            </div>
  
  
            <div class="mb-3">
              <label for="stok" class="form-label">Stok</label>
              <input type="number" class="form-control" name="stok" placeholder="Masukkan jumlah stok" required>
            </div>
  
            <div class="mb-3">
              <label for="deskripsi" class="form-label">Deskripsi</label>
              <textarea class="form-control" name="deskripsi" placeholder="Masukkan deskripsi barang"></textarea>
            </div>

            <div class="mb-3">
              <label for="tanggal" class="form-label">Pilih Tanggal</label>
              <input type="date" class="form-control" name="tanggal" required>
            </div>
            
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
      
    </div>
  </div>
</div>
@endsection


@section('script')
  <!-- Toastr CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <script>
    $('#formBarang').on('submit', function(e) {
      e.preventDefault();
      let form = $(this);
      let formData = form.serialize();

      $.ajax({
        url: "/barang-masuk/store",
        type: "POST",
        data: formData,
        success: function(response) {
          toastr.success("Barang berhasil disimpan!");
          $('#exampleModal').modal('hide');
          form[0].reset(); // Reset form
          location.reload(); // Reload tabel otomatis
        },
        error: function(xhr) {
          var errorMessage = xhr.responseJSON.error || "Gagal menyimpan data!";
          toastr.error(errorMessage); 
        }
      });
    });

    $('.editBarang').on('click', function () {
      let id = $(this).data('id');
      let kode = $(this).data('kode');
      let nama = $(this).data('nama');
      let stok = $(this).data('stok');
      let deskripsi = $(this).data('deskripsi');

      console.log(id)

      $('#exampleModalEdit').modal('show');
      $('input[name="kode_barang"]').val(kode);
      $('input[name="nama_barang"]').val(nama);
      $('input[name="stok"]').val(stok);
      $('textarea[name="deskripsi"]').val(deskripsi);

      $('#formBarangUpdate').submit(function (e) {
      e.preventDefault();
      let url = $(this).attr('action');

      $.ajax({
        url: `/barang-masuk/update/${id}`,
        method: 'POST',
        data: $(this).serialize(),
        success: function () {
          toastr.success("Barang berhasil diupdate!");
          $('#exampleModalEdit').modal('hide');
          setTimeout(() => {
            location.reload();
          }, 1000); // Reload halaman setelah 1 detik
        },
        error: function (xhr) {

          var errorMessage = xhr.responseJSON.error || "Gagal update data!";
          toastr.error(errorMessage); 
        }
      });
    });

    });

    


    $('.deleteBarangMasuk').on('click', function() {
      let id = $(this).data('id');
      console.log(`/barang-masuk/delete/${id}`)
      if (confirm("Apakah kamu yakin ingin menghapus data ini?")) {
        $.ajax({
          url: `/barang-masuk/delete/${id}`,
          type: "DELETE",
          headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
          success: function(response) {
            toastr.success("Barang berhasil dihapus!");
            location.reload();
          },
          error: function() {
            var errorMessage = xhr.responseJSON.error || "Gagal menghapus data!";
            toastr.error(errorMessage); 
            
          }
        });
      }
    });


  </script>
@endsection