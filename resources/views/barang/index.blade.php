<h2>List Barang</h2>
<a href="{{ route('barang.create') }}">Tambah Barang</a>
<table border="1">
    <tr>
        <th>Nama</th>
        <th>Stok</th>
        <th>Aksi</th>
    </tr>
    @foreach($barang as $item)
    <tr>
        <td>{{ $item->nama_barang }}</td>
        <td>{{ $item->stok }}</td>
        <td>
            <a href="{{ route('barang.edit', $item->id) }}">Edit</a>
            <form action="{{ route('barang.destroy', $item->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
