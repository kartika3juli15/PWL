<form id="form_store" method="POST" action="{{ url('/penjualan_detail/store_ajax') }}">
    @csrf
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Tambah Detail Penjualan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            </div>

            <div class="modal-body">
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

        // Hitung total harga
        $('#harga, #jumlah').on('input', function () {
            const harga = parseFloat($('#harga').val()) || 0;
            const jumlah = parseInt($('#jumlah').val()) || 0;
            $('#total_harga').val(harga * jumlah);
        });

        // Submit form via AJAX
        $('#form_store').on('submit', function (e) {
            e.preventDefault();
            const formData = $(this).serialize();

            $.post("{{ url('/penjualan_detail/store_ajax') }}", formData, function (res) {
                if (res.status) {
                    $('#myModal').modal('hide');
                    $('#table_penjualan_detail').DataTable().ajax.reload();
                } else {
                    alert("Gagal menyimpan data!");
                }
            }).fail(function (xhr) {
                const errors = xhr.responseJSON?.msgField || {};
                let errorMsg = 'Terjadi kesalahan:\n';
                for (const key in errors) {
                    errorMsg += `${errors[key][0]}\n`;
                }
                alert(errorMsg);
            });
        });
    });
</script>
