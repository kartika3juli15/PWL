@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Kategori</h3>
        <div class="card-tools">
            <div class="row">
                <div class="dropdown mr-2">
                    <button onclick="modalAction('{{ url('/kategori/import') }}')" class="btn btn-primary mr-2">
                        Import Data
                    </button>
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="importExportDropdownPenjualan" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Export
                    </button>
                    <div class="dropdown-menu" aria-labelledby="importExportDropdownPenjualan">
                        <a class="dropdown-item" href="{{ url('/kategori/export_excel') }}">
                            <i class="fa fa-file-excel"></i> Export to Excel
                        </a>
                        <a class="dropdown-item" href="{{ url('/kategori/export_pdf') }}" target="_blank">
                            <i class="fa fa-file-pdf"></i> Export to PDF
                        </a>
                    </div>
                </div>

                <button onclick="modalAction('{{ url('/kategori/create_ajax') }}')" class="btn btn-success mr-2">
                    Tambah Data
                </button>                    
            </div>
        </div>
    </div>
    <div class="card-body">
        
        <!-- Filter data -->
        <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-sm row text-sm mb-0">
                        <label for="filter_date" class="col-md-1 col-form-label">Filter</label>
                        <div class="col-md-3">
                            <select name="filter_kategori" class="form-control form-control-sm filter_kategori">
                                <option value="">- Semua -</option>
                                @foreach($kategori as $l)
                                    <option value="{{ $l->kategori_id }}">{{ $l->kategori_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Kategori Barang</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-sm table-striped table-hover" id="table-barang">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div>
@endsection

@push('css')
<style>
    .aksi-buttons {
        display: flex;
        gap: 6px;
        justify-content: center;
        flex-wrap: wrap;
    }
</style>
@endpush

@push('js')
<script>
function modalAction(url = '') {
    $('#myModal').load(url, function() {
        $('#myModal').modal('show');
    });
}

var tableBarang;

$(document).ready(function() {
    tableBarang = $('#table-barang').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('barang/list') }}",
            dataType: "json",
            type: "POST",
            data: function(d) {
                d.filter_kategori = $('.filter_kategori').val();
            }
        },
        columns: [
            {
                data: "DT_RowIndex", 
                className: "text-center",
                width: "5%",
                orderable: false,
                searchable: false
            },
            {
                data: "barang_kode",
                className: "",
                width: "10%"
            },
            {
                data: "barang_nama",
                className: "",
                width: "30%"
            },
            {
                data: "harga_beli",
                className: "",
                width: "10%",
                render: function(data) {
                    return new Intl.NumberFormat('id-ID').format(data);
                }
            },
            {
                data: "harga_jual",
                className: "",
                width: "10%",
                render: function(data) {
                    return new Intl.NumberFormat('id-ID').format(data);
                }
            },
            {
                data: "kategori.kategori_nama",
                className: "",
                width: "14%"
            },
            {
                data: "aksi",
                className: "text-center",
                width: "21%",
                orderable: false,
                searchable: false
            }
        ]
    });

    $('#table-barang_filter input').unbind().bind().on('keyup', function(e) {
        if (e.keyCode == 13) {
            tableBarang.search(this.value).draw();
        }
    });

    $('.filter_kategori').change(function() {
        tableBarang.draw();
    });
});
</script>
@endpush
