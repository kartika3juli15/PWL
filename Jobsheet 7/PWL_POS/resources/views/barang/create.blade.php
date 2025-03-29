@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Tambah Barang</h1>
        <form action="{{ route('barang.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Kode Barang</label>
                <input type="text" name="barang_kode" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Nama Barang</label>
                <input type="text" name="barang_nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Harga Beli</label>
                <input type="number" name="harga_beli" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Harga Jual</label>
                <input type="number" name="harga_jual" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Kategori</label>
                <select name="kategori_id" class="form-control" required>
                    @foreach($kategori as $k)
                        <option value="{{ $k->kategori_id }}">{{ $k->kategori_nama }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection