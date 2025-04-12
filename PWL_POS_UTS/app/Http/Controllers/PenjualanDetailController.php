<?php

namespace App\Http\Controllers;

use App\Models\PenjualanDetailModel;
use App\Models\BarangModel;
use App\Models\PenjualanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PenjualanDetailController extends Controller
{
    public function getHargaBarang($id)
{
    $barang = BarangModel::find($id);
    if ($barang) {
        return response()->json(['harga_jual' => $barang->harga_jual]);
    } else {
        return response()->json(['harga_jual' => 0]);
    }
}

    public function index()
    {
        $activeMenu = 'penjualan_detail';
        $breadcrumb = (object)[
            'title' => 'Data Detail Penjualan',
            'list' => ['Home', 'Penjualan', 'Detail']
        ];
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $penjualan = PenjualanModel::select('penjualan_id', 'penjualan_kode')->get();

        return view('penjualan_detail.index', compact('activeMenu', 'breadcrumb', 'barang', 'penjualan'));
    }

    public function list(Request $request)
    {
        $PenjualanDetail = PenjualanDetailModel::with(['barang', 'penjualan'])
            ->select('detail_id', 'penjualan_id', 'barang_id', 'harga', 'jumlah', 'metode_pembayaran');

        if ($request->filled('filter_penjualan')) {
            $PenjualanDetail->where('penjualan_id', $request->filter_penjualan);
        }

        return DataTables::of($PenjualanDetail)
            ->addIndexColumn()
            ->addColumn('barang_nama', fn ($d) => $d->barang->barang_nama ?? '-')
            ->addColumn('penjualan_kode', fn ($d) => $d->penjualan->penjualan_kode ?? '-')
            ->addColumn('total_harga', fn ($d) => $d->harga * $d->jumlah)
            ->addColumn('aksi', function ($d) {
                $btn = '<button onclick="modalAction(\'' . url('/penjualan_detail/' . $d->id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan_detail/' . $d->id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan_detail/' . $d->id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $penjualan = PenjualanModel::select('penjualan_id', 'penjualan_kode')->get();
        return view('penjualan_detail.create_ajax', compact('barang', 'penjualan'));
    }

    public function store_ajax(Request $request)
{
    if ($request->ajax()) {
        $validator = Validator::make($request->all(), [
            'penjualan_id' => 'required|exists:t_penjualan,penjualan_id',
            'barang_id' => 'required|exists:m_barang,barang_id',
            'jumlah' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'msgField' => $validator->errors()
            ]);
        }

        // Ambil harga dari model Barang (kolom harga_jual)
        $barang = BarangModel::find($request->barang_id);
        if (!$barang) {
            return response()->json([
                'status' => false,
                'message' => 'Barang tidak ditemukan'
            ]);
        }

        PenjualanDetailModel::create([
            'penjualan_id' => $request->penjualan_id,
            'barang_id' => $request->barang_id,
            'harga' => $barang->harga_jual, // Ambil dari database
            'jumlah' => $request->jumlah,
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data detail penjualan berhasil disimpan'
        ]);
    }

    return redirect('/');
}


    public function edit_ajax($id)
    {
        $PenjualanDetail = PenjualanDetailModel::findOrFail($id);
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $penjualan = PenjualanModel::select('penjualan_id', 'penjualan_kode')->get();

        return view('penjualan_detail.edit_ajax', compact('PenjualanDetail', 'barang', 'penjualan'));
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'penjualan_id' => 'required|exists:t_penjualan,penjualan_id',
                'barang_id' => 'required|exists:m_barang,barang_id',
                'harga' => 'required|numeric',
                'jumlah' => 'required|integer|min:1',
                'metode_pembayaran' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'msgField' => $validator->errors()
                ]);
            }

            $PenjualanDetail = PenjualanDetailModel::findOrFail($id);
            $PenjualanDetail->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diperbarui'
            ]);
        }

        return redirect('/');
    }

    public function confirm_ajax($id)
    {
        $PenjualanDetail = PenjualanDetailModel::findOrFail($id);
        return view('penjualan_detail.confirm_ajax', compact('PenjualanDetail'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax()) {
            $PenjualanDetail = PenjualanDetailModel::find($id);

            if ($PenjualanDetail) {
                $PenjualanDetail->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return redirect('/penjualan_detail');
    }
}
