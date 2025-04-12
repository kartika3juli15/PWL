@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Detail Penjualan</h3>
        <div class="card-tools">
            <div class="row">
                <div class="dropdown mr-2">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="importExportDropdownPenjualan" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Export
                    </button>
                    <div class="dropdown-menu" aria-labelledby="importExportDropdownPenjualan">
                        <a class="dropdown-item" href="{{ url('/penjualan_detail/export_excel') }}">
                            <i class="fa fa-file-excel"></i> Export to Excel
                        </a>
                        <a class="dropdown-item" href="{{ url('/penjualan_detail/export_pdf') }}" target="_blank">
                            <i class="fa fa-file-pdf"></i> Export to PDF
                        </a>
                    </div>
                </div>

                <button onclick="modalAction('{{ url('/penjualan_detail/create_ajax') }}')" class="btn btn-success mr-2">
                    Tambah Data
                </button>                    
            </div>
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
                            <select class="form-control" id="penjualan_id" name="penjualan_id">
                                <option value="">- Semua</option>
                                @foreach ($penjualan as $item)
                                    <option value="{{ $item->penjualan_id }}">{{ $item->penjualan_kode }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Kode Penjualan</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_penjualan_detail">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Penjualan</th>
                        <th>Nama Barang</th>
                        <th>Harga Per Barang</th>
                        <th>Jumlah Barang</th>
                        <th>Total Harga</th>
                        <th>Metode Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }

    $(document).ready(function () {
        const table = $('#table_penjualan_detail').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('penjualan_detail/list') }}",
                type: "POST",
                data: function (d) {
                    d.penjualan_id = $('#penjualan_id').val();
                    d._token = "{{ csrf_token() }}";
                }
            },
            columns: [
                { 
                    data: "DT_RowIndex", 
                    className: "text-center", 
                    orderable: false, 
                    searchable: false, 
                    width: "5%" 
                },
                { 
                    data: "penjualan.penjualan_kode", 
                    className: "text-center", 
                    orderable: false, 
                    searchable: false, 
                    width: "10%" 
                },
                { 
                    data: "barang.barang_nama", 
                    className: "text-center", 
                    orderable: false, 
                    searchable: false 
                },
                { 
                    data: "harga", 
                    className: "text-center", 
                    orderable: true, 
                    searchable: false 
                },
                { 
                    data: "jumlah", 
                    className: "text-center", 
                    orderable: true, 
                    searchable: false, 
                    width: "8%" 
                },
                { 
                    data: "total_harga", 
                    className: "text-center", 
                    orderable: true, 
                    searchable: false 
                },
                { 
                    data: "metode_pembayaran", 
                    className: "text-center", 
                    orderable: false, 
                    searchable: false, 
                    width: "10%"
                },
                { 
                    data: "aksi", 
                    className: "text-center", 
                    orderable: false, searchable: 
                    false, width: "21%" 
                }
            ]

        });

        $('#penjualan_id').on('change', function () {
            table.ajax.reload();
        });
    });
</script>
@endpush
