@empty($penjualan)
<div id="modal-master" class="modal-dialog modal-lg" role="document"> 
    <div class="modal-content"> 
        <div class="modal-header"> 
            <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5> 
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button> 
        </div> 
        <div class="modal-body"> 
            <div class="alert alert-danger"> 
                <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5> 
                Data yang anda cari tidak ditemukan
            </div> 
            <a href="{{ url('/penjualan') }}" class="btn btn-warning">Kembali</a> 
        </div> 
    </div> 
</div> 
@else
<div id="modal-master" class="modal-dialog modal-lg" role="document"> 
    <div class="modal-content"> 
        <div class="modal-header"> 
            <h5 class="modal-title" id="exampleModalLabel">Detail Data Penjualan</h5> 
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button> 
        </div> 
        <div class="modal-body"> 
            <div class="form-group"> 
                <label>Kode Penjualan</label> 
                <input type="text" class="form-control" value="{{ $penjualan->penjualan_kode }}" readonly>
            </div>
            <div class="form-group"> 
                <label>Nama Pembeli</label> 
                <input type="text" class="form-control" value="{{ $penjualan->pembeli }}" readonly>
            </div> 
            <div class="form-group"> 
                <label>Tanggal Penjualan</label> 
                <input type="text" class="form-control" value="{{ $penjualan->penjualan_tanggal }}" readonly>
            </div> 
        </div> 
        <div class="modal-footer"> 
            <button type="button" data-dismiss="modal" class="btn btn-secondary">Kembali</button> 
        </div> 
    </div> 
</div> 
@endempty