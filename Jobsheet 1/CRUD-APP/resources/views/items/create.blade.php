<!DOCTYPE html>
<html>
<head>
    <title>Add Item</title>
</head>
<body>
    <h1>Add Item</h1>

    <form action="{{ route('items.store') }}" method="POST"> <!--Membuka form dengan metode POST untuk mengirim data ke rute items.store-->
        @csrf<!--menambahkan token CSRF-->
        <label for="name">Name:</label> <!--label untuk input nama-->
        <input type="text" name="name" required> <!--digunakan untuk menangkap data input saat form dikirim dan wajib diisi-->

        <br>

        <label for="description">Description:</label> <!--label untuk input deskripsi.-->
        <textarea name="description" required></textarea> <!--input teks berbentuk textarea untuk memasukkan deskripsi item dan wajib diisi-->

        <br>

        <button type="submit">Add Item</button> <!--tombol submit-->
    </form>

    <a href="{{ route('items.index') }}">Back to List</a> <!--tautan untuk kembali ke halaman daftar item-->
</body>
</html>
