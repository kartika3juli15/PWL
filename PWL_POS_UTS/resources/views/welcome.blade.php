@extends('layouts.template')

@section('content')
<style>
    body {
        background-image: url('{{ asset('storage/mall.jpeg') }}');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }
    .card {
        background-color: rgba(255, 255, 255, 0.9);
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
