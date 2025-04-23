@extends('layouts.app')

@section('title', 'Konfirmasi Logout')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white text-center">
                    <h4>Konfirmasi Logout</h4>
                </div>
                <div class="card-body text-center">
                    <p class="mb-4">Apakah Anda yakin ingin keluar dari aplikasi?</p>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt"></i> Log out
                        </button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">
                            Batal
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
