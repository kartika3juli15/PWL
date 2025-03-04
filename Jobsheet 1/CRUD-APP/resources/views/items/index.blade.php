<!DOCTYPE html>
<html>
<head>
    <title>Item List</title> <!--judul pada tab-->
</head>
<body>
    <h1>Items</h1> <!--judul halaman -->

    @if(session('success')) <!--mengecek apakah ada pesan sukses-->
        <p>{{ session('success') }}</p> <!--menampilkan pesan sukses-->
    @endif <!--menutup pernyataan @if-->

    <a href="{{ route('items.create') }}">Add Item</a> <!--Menampilkan tautan "Add Item" yang mengarah ke halaman pembuatan item baru menggunakan route 'items.create'-->

    <ul> <!--Membuka daftar tidak berurutan -->
        @foreach ($items as $item) <!--melakukan perulangan untuk setiap item dalam koleksi $items-->
            <li> 
                {{ $item->name }} <!--menampilkan nama item dalam <li> list item-->
                <a href="{{ route('items.edit', $item) }}">Edit</a> <!--menampilkan tautan "Edit" untuk mengedit item-->
                <form action="{{ route('items.destroy', $item) }}" method="POST" style="display:inline;"> <!--membuat form untuk menghapus item dengan metode POST-->
                    @csrf <!--menambahkan token keamanan CSRF-->
                    @method('DELETE') <!--mengubah metode request menjadi DELETE-->
                    <button type="submit">Delete</button> <!--tombol delete-->
                </form>
            </li>
        @endforeach <!--mengakhiri perulangan-->
    </ul>
</body>
</html>
