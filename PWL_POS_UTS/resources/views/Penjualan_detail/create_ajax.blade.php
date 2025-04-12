<form id="form_store" method="POST" action="{{ url('/penjualan_detail/store_ajax') }}">
    @csrf
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Tambah Detail Penjualan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            </div>

            <div class="modal-body">

                {{-- kode --}}
                <div class="form-group">
                    <label>Kode Penjualan</label>
                    <select name="penjualan_id" id="penjualan_id" class="form-control" required>
                        <option value="">- Pilih Kode -</option>
                        @foreach ($penjualan as $b)
                            <option value="{{ $b->penjualan_id }}">{{ $b->penjualan_kode }}</option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Pilih Barang --}}
                <div class="form-group">
                    <label>Nama Barang</label>
                    <select name="barang_id" id="barang_id" class="form-control" required>
                        <option value="">- Pilih Barang -</option>
                        @foreach ($barang as $b)
                            <option value="{{ $b->barang_id }}">{{ $b->barang_nama }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Harga --}}
                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" name="harga" id="harga" class="form-control" placeholder="Harga" readonly>
                </div>

                {{-- Jumlah --}}
                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="Jumlah" required>
                </div>

                {{-- Total Harga --}}
                <div class="form-group">
                    <label>Total Harga</label>
                    <input type="number" name="total_harga" id="total_harga" class="form-control" placeholder="Total Harga" readonly>
                </div>

                {{-- Metode Pembayaran --}}
                <div class="form-group">
                    <label>Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="form-control" required>
                        <option value="cash">Cash</option>
                        <option value="bank">Bank</option>
                        <option value="e-money">E-Money</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        // Auto-fill harga saat barang dipilih
        $('#barang_id').on('change', function () {
            const barangID = $(this).val();
            if (barangID) {
                $.getJSON(`/penjualan_detail/get_harga_barang/${barangID}`, function (data) {
                    $('#harga').val(data.harga_jual).trigger('input');
                });
            } else {
                $('#harga').val('');
                $('#total_harga').val('');
            }
        });

        // Hitung total harga secara otomatis
        $('#harga, #jumlah').on('input', function () {
            const harga = parseFloat($('#harga').val()) || 0;
            const jumlah = parseInt($('#jumlah').val()) || 0;
            $('#total_harga').val(harga * jumlah);
        });

        // Submit form via AJAX
        $('#form_store').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function (response) {
                    if (response.status) {
                        $('#myModal').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'Data berhasil disimpan.'
                        });

                        if (typeof dataPenjualan !== 'undefined') {
                            dataPenjualan.ajax.reload();
                        } else {
                            $('#table_penjualan_detail').DataTable().ajax.reload();
                        }
                    } else {
                        $('.error-text').text('');
                        $.each(response.msgField, function (prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });

                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message || 'Gagal menyimpan data.'
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal menyimpan data.'
                    });
                }
            });
        });
    });
</script>


