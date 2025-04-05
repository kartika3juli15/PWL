@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('stok/create') }}">Tambah</a>
                <button onclick="modalAction('{{ url('/stok/create_ajax/') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="user_id" name="user_id">
                                <option value="">- Semua</option>
                                @foreach ($user as $item)
                                    <option value="{{ $item->user_id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Nama User</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_stok">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>Nama User</th>
                        <th>Tanggal Stok</th>
                        <th>Jumlah Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div> 
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data- backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div> 
@endsection

@push('css') 
@endpush

@push('js')
    <script>
        function modalAction(url = ''){
            $('#myModal').load(url,function(){
                $('#myModal').modal('show');
            });
        }
        
        $(document).ready(function() {
            var dataStok = $('#table_stok').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('stok/list') }}", 
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.user_id = $('#user_id').val(); // Filter berdasarkan user_id
                        d._token = "{{ csrf_token() }}"; // Tambahkan CSRF token
                    }
                },
                columns: [
                    {
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false, 
                        searchable: false
                    },
                    {
                        data: "barang.barang_nama", // Mengambil nama barang dari relasi
                        className: "",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "user.nama", // Mengambil nama user dari relasi
                        className: "",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "stok_tanggal",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "stok_jumlah",
                        className: "",
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: "aksi",
                        className: "",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#user_id').on('change', function() {
                dataStok.ajax.reload();
            });
        });
    </script> 
@endpush