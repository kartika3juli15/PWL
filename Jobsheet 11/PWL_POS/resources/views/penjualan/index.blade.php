@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row w-100">
            <div class="col-md-6 d-flex align-items-center">
                <h3 class="card-title mb-0">Daftar Penjualan</h3>
            </div>
            <div class="col-md-6 d-flex justify-content-start justify-content-md-end">
                <div class="btn-group mr-2">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Export
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ url('/penjualan/export_excel') }}">
                            <i class="fa fa-file-excel"></i> Export to Excel
                        </a>
                        <a class="dropdown-item" href="{{ url('/penjualan/export_pdf') }}" target="_blank">
                            <i class="fa fa-file-pdf"></i> Export to PDF
                        </a>
                    </div>
                </div>

                <button onclick="modalAction('{{ url('/penjualan/create_ajax') }}')" class="btn btn-success">
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

        <table class="table table-bordered table-hover table-sm table-striped" id="table_penjualan">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Penjualan</th>
                    <th>Tanggal</th>
                    <th>Pembeli</th>
                    <th>Pegawai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal container -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"></div>
@endsection

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');

            $('#form-penjualan').on('submit', function (e) {
                e.preventDefault();

                let form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    dataType: 'json',
                    success: function (res) {
                        if (res.status) {
                            $('#myModal').modal('hide');
                            $('#table_penjualan').DataTable().ajax.reload();
                            Swal.fire('Sukses', res.message, 'success');
                        } else {
                            Swal.fire('Gagal', res.message ?? 'Validasi gagal.', 'error');
                            console.log(res.msgField);
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data.', 'error');
                    }
                });
            });
        });
    }

    $(document).ready(function () {
        $('#table_penjualan').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('penjualan/list') }}",
                type: 'POST',
                data: function (d) {
                    // Tambahkan filter jika diperlukan
                }
            },
            columns: [
                { 
                    data: 'DT_RowIndex', 
                    className: "text-center", 
                    width: "5%", 
                    orderable: false, 
                    searchable: false 
                },
                { 
                    data: 'penjualan_kode', 
                    name: 'penjualan_kode', 
                    width: "15%" 
                },
                { 
                    data: 'penjualan_tanggal', 
                    name: 'penjualan_tanggal', 
                    width: "20%" 
                },
                { 
                    data: 'pembeli', 
                    name: 'pembeli', 
                    width: "20%" 
                },
                {
                    data: 'user_nama',
                    name: 'user.username',
                    width: "15%"
                },
                { 
                    data: 'aksi', 
                    className: "text-center",
                    width: "15%", 
                    orderable: false, 
                    searchable: false 
                }
            ]
        });
    });
</script>
@endpush
