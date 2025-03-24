@extends('layouts.app')
 
{{-- Customize layout sections --}}
 
@section('subtitle', 'Kategori')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Kategori')
 
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                Manage Kategori
                <!-- Tambahkan Button Add -->
                <a href="{{ url('/kategori/create') }}" class="btn btn-primary float-right">Add</a>
            </div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection
 
@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
