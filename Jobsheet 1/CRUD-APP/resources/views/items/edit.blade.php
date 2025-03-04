<!DOCTYPE html>
<html>
<head>
    <title>Edit Item</title>
</head>
<body>
    <h1>Edit Item</h1>
    <form action="{{ route('items.update', $item) }}" method="POST"> <!--membuka form dengan metode POST untuk-->
        @csrf <!--menambahkan token CSRF-->
        @method("PUT") <!--Laravel hanya mendukung metode GET dan POST dalam form HTML-->
        <label for="name">Name:</label> <!--label untuk input nama-->
        <input type="text" name="name" value="{{ $item->name }}" required> <!--mengisi input dengan nama item yang sedang diedit dan wajib diisi-->
        <br>
        <label for="description">Description:</label> <!--label untuk input deskripsi-->
        <textarea name="description" required>{{ $item->description }}</textarea> <!--mngisi textarea dengan deskripsi item yang sedang diedit-->
        <br>
        <button type="submit">Update Item</button> <!--tombol untuk mengirimkan form dan memperbarui item di database-->
    </form>
    <a href="{{ route('items.index') }}">Back to List</a> <!--tautan untuk kembali ke halaman daftar item-->
</body>
</html>
