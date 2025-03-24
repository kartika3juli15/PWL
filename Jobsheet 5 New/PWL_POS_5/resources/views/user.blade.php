<!DOCTYPE html>
<html>

<head>
    <title>Data User</title>
    <style>
        .btn {
            display: inline-block;
            padding: 5px 10px;
            margin: 2px;
            text-decoration: none;
            color: white;
            border-radius: 3px;
            font-size: 14px;
        }

        .btn-detail {
            background-color: #17a2b8; /* Biru */
        }

        .btn-edit {
            background-color: #ffc107; /* Kuning */
            color: black;
        }

        .btn-delete {
            background-color: #dc3545; /* Merah */
        }
    </style>
</head>

<body>
    <h1>Data User</h1>
    <a href="/user/tambah">+ Tambah User</a>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Nama</th>
                <th>Level Kode</th>
                <th>Level Nama</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
                <tr>
                    <td>{{ $d->user_id }}</td>
                    <td>{{ $d->username }}</td>
                    <td>{{ $d->nama }}</td>
                    <td>{{ $d->level->level_kode }}</td>
                    <td>{{ $d->level->level_nama }}</td>
                    <td>
                        <a href="/user/detail/{{ $d->user_id }}" class="btn btn-detail">Detail</a>
                        <a href="/user/ubah/{{ $d->user_id }}" class="btn btn-edit">Edit</a>
                        <a href="/user/hapus/{{ $d->user_id }}" class="btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">Hapus</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
