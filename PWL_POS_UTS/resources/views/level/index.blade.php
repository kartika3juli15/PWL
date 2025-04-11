@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Level</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/level/import') }}')" class="btn btn-info">Import Level</button>
            <a href="{{ url('/level/export_excel') }}" class="btn btn-primary">Export Excel</a>
            <a href="{{ url('/level/export_pdf') }}" class="btn btn-warning">Export PDF</a>
            <button onclick="modalAction('{{ url('/level/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button>
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
