@extends('layouts.template')

@section('content')
<style>
    .content-wrapper {
        background-image: url('{{ asset('storage/mall.jpeg') }}');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        min-height: 100vh;
        padding: 20px;
    }

    .card {
        background-color: rgba(255, 255, 255, 0.95); /* Lebih solid, biar gak terlalu transparan */
    }

    .content-header h1, /* Untuk Selamat Datang */
    .breadcrumb {
        color: white !important;
        text-shadow: 1px 1px 2px black; /* Tambah bayangan supaya lebih jelas */
    }
</style>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Halo, {{ Auth::user()->username ?? 'User' }}!</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        Selamat datang {{ Auth::user()->username ?? 'User' }}, ini adalah halaman utama dari aplikasi ini.
    </div>
</div>
@endsection
