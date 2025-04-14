<?php

namespace App\Http\Controllers;

use App\Models\PenjualanDetailModel;
use App\Models\BarangModel;
use App\Models\PenjualanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

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
    if ($request->ajax()) {
        $PenjualanDetail = PenjualanDetailModel::with(['penjualan', 'barang']);

        return DataTables::of($PenjualanDetail)
            ->addIndexColumn()
            ->addColumn('penjualan_kode', function ($row) {
                return $row->penjualan->penjualan_kode ?? '-';
            })
            ->addColumn('barang_nama', function ($row) {
                return $row->barang->barang_nama ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                return '<button onclick="modalAction(\'' . url('penjualan_detail/' . $row->detail_id . '/confirm_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
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
            'harga' => 'required|numeric',
            'jumlah' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
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


    public function confirm_ajax($id)
    {
        $PenjualanDetail = PenjualanDetailModel::with(['barang', 'penjualan'])->find($id);
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


    public function export_excel()
    {
        // Ambil data penjualan detail untuk diexport
        $penjualanDetails = PenjualanDetailModel::select('penjualan_id', 'barang_id', 'harga', 'jumlah', 'total_harga', 'metode_pembayaran')
            ->with(['penjualan', 'barang']) // pastikan relasi 'barang' dan 'penjualan' ada
            ->orderBy('penjualan_id')
            ->get();
    
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Penjualan');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Harga Per Barang');
        $sheet->setCellValue('E1', 'Jumlah Barang');
        $sheet->setCellValue('F1', 'Total Harga');
        $sheet->setCellValue('G1', 'Metode Pembayaran');
    
        $sheet->getStyle('A1:G1')->getFont()->setBold(true); // Bold header
    
        $no = 1;
        $baris = 2;
    
        foreach ($penjualanDetails as $detail) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $detail->penjualan->penjualan_kode ?? '-');
            $sheet->setCellValue('C' . $baris, $detail->barang->barang_nama ?? '-');
            $sheet->setCellValue('D' . $baris, $detail->harga);
            $sheet->setCellValue('E' . $baris, $detail->jumlah);
            $sheet->setCellValue('F' . $baris, $detail->total_harga);
            $sheet->setCellValue('G' . $baris, $detail->metode_pembayaran);
    
            $baris++;
            $no++;
        }
    
        // Auto-size kolom
        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    
        $sheet->setTitle('Data Transaksi');
    
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_Transaksi_' . date('Y-m-d_H-i-s') . '.xlsx';
    
        // Header response untuk download file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Pragma: public');
    
        $writer->save('php://output');
        exit;
    }
    
    public function export_pdf()
{
    $PenjualanDetail = PenjualanDetailModel::select('penjualan_id', 'barang_id', 'harga', 'jumlah', 'total_harga', 'metode_pembayaran')
        ->orderBy('penjualan_id', 'asc')
        ->orderBy('barang_id', 'asc')
        ->orderBy('detail_id', 'asc')
        ->get();

    $pdf = Pdf::loadView('Penjualan_detail.export_pdf', [
        'PenjualanDetail' => $PenjualanDetail
    ]);

    $pdf->setPaper('a4', 'portrait');
    $pdf->setOption("isRemoteEnabled", true);
    $pdf->render();

    return $pdf->stream('Data_Transaksi_' . date('Y-m-d_H-i-s') . '.pdf');
}

}    