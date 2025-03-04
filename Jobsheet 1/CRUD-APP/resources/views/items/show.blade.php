<!DOCTYPE html>
<html>
<head>
    <title>Item List</title>
</head>
<body>
    <h1>Items</h1> <!--judul halaman -->
    @if(session('success')) <!--mengecek apakah ada pesan sukses dalam sesi Laravel-->
        <p>{{ session('success') }}</p> <!--tampilkan pesan sukses dalam elemen <p>-->
    @endif <!--menutup pernyataan kondisi @if-->
    <a href="{{ route('items.create') }}">Add Item</a> <!--tautan untuk menuju halaman menambahkan item baru-->
    <ul>
        @foreach($items as $item) <!--iterasi pada daftar item ($items)-->
            <li> <!--membuka elemen list item-->
                {{ $item->name }} <!--menampilkan nama item-->
                <a href="{{ route('items.edit', $item) }}">Edit</a> <!--tautan untuk mengedit item-->
                <form action="{{ route('items.destroy', $item) }}" method="POST" style="display:inline;"> <!--membuka form dengan metode POST untuk menghapus item agar form tampil dalam satu baris bersama teks lainnya-->
                    @csrf <!--menambahkan token CSRF untuk keamanan-->
                    @method('DELETE') <!--laravel hanya mendukung metode GET dan POST, sehingga untuk menghapus-->
                    <button type="submit">Delete</button> <!--tombol untuk menghapus item-->
                </form>
            </li>
        @endforeach <!--menutup perulangan foreach-->
    </ul> <!--menutup daftar unordered list-->
</body>
</html>
