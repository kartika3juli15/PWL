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

        <!-- Filter Kategori -->
        <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-sm row text-sm mb-0">
                        <label for="filter_level" class="col-md-1 col-form-label">Filter</label>
                        <div class="col-md-3">
                            <select name="filter_level" class="form-control form-control-sm filter_kategori">
                                <option value="">- Semua -</option>
                            </select>
                            <small class="form-text text-muted">Kategori Level</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-hover table-sm" id="table_level">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Level</th>
                        <th>Nama Level</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" 
    data-keyboard="false" data-width="75%"></div>
    @endsection

@push('js')
         <script>
        function modalAction(url = ''){
        $('#myModal').load(url,function(){
            $('#myModal').modal('show');
        });
    }
        var tableLevel;  
        $(document).ready(function () {
            dataLevel = $('#table_level').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('level/list') }}",
                    dataType: "json",
                    type: "POST"
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
                        data: "level_kode",
                        width: "10%",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "level_nama",
                        width: "37%",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "aksi",
                        className: "text-center",
                        width: "14%",
                        orderable: false,
                        searchable: false
                    }
                ]
            });
            $('#table_level_filter input').unbind().bind('keyup', function(e) {
        if (e.keyCode == 13) {
            tableLevel.search(this.value).draw();
        }
    });

    $('.filter_kategori').change(function() {
        tableLevel.draw();
    });
});
</script>
@endpush
