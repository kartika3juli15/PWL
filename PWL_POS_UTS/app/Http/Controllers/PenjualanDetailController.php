<?php

namespace App\Http\Controllers;

use App\Models\PenjualanDetailModel;
use App\Models\BarangModel;
use App\Models\PenjualanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanDetailController extends Controller
{
    //MENAMPILKAN HARGA DENGAN OTOMATIS (MENGGABUNGKAN KE TABEL BARANG)
    public function getHargaBarang($id)
    {
        $barang = BarangModel::find($id);
        return response()->json([
            'harga_jual' => $barang ? $barang->harga_jual : 0
        ]);
    }

    public function index()
    {
        $activeMenu = 'penjualan_detail';
        $breadcrumb = (object)[
            'title' => 'Data Detail Penjualan',
            'list'  => ['Home', 'Penjualan', 'Detail']
        ];
        $barang    = BarangModel::select('barang_id', 'barang_nama')->get();
        $penjualan = PenjualanModel::select('penjualan_id', 'penjualan_kode')->get();

        return view('penjualan_detail.index', compact('activeMenu', 'breadcrumb', 'barang', 'penjualan'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $details = PenjualanDetailModel::with(['penjualan', 'barang']);

            if ($request->filled('penjualan_id')) {
                $details->where('penjualan_id', $request->penjualan_id);
            }

            return DataTables::of($details)
                ->addIndexColumn()
                ->addColumn('penjualan_kode', function ($row) {
                    return $row->penjualan->penjualan_kode ?? '-';
                })
                ->addColumn('barang_nama', function ($row) {
                    return $row->barang->barang_nama ?? '-';
                })
                ->addColumn('aksi', function ($row) {
                    return '<button onclick="modalAction(\''.
                        url("penjualan_detail/{$row->detail_id}/confirm_ajax").
                    '\')" class="btn btn-danger btn-sm">Hapus</button>';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }

    public function create_ajax()
    {
        $barang    = BarangModel::select('barang_id', 'barang_nama')->get();
        $penjualan = PenjualanModel::select('penjualan_id', 'penjualan_kode')->get();
        return view('penjualan_detail.create_ajax', compact('barang', 'penjualan'));
    }

    public function store_ajax(Request $request)
    {
        if (! $request->ajax()) {
            return redirect('/');
        }

        $validator = Validator::make($request->all(), [
            'penjualan_id'      => 'required|exists:t_penjualan,penjualan_id',
            'barang_id'         => 'required|exists:m_barang,barang_id',
            'jumlah'            => 'required|integer|min:1',
            'metode_pembayaran' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'   => false,
                'message'  => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        //PENGAMBILAN STOK
        $barang = BarangModel::find($request->barang_id);
        $stok   = DB::table('t_stok')
                    ->where('barang_id', $barang->barang_id)
                    ->first();

        if (! $stok || $stok->stok_jumlah < $request->jumlah) {
            return response()->json([
                'status'  => false,
                'message' => 'Stok tidak mencukupi atau tidak ditemukan'
            ]);
        }

        DB::transaction(function () use ($request, $barang) {
            PenjualanDetailModel::create([
                'penjualan_id'      => $request->penjualan_id,
                'barang_id'         => $barang->barang_id,
                'harga'             => $barang->harga_jual,
                'jumlah'            => $request->jumlah,
                'metode_pembayaran' => $request->metode_pembayaran,
            ]);
            
            //pengurangan stok
            DB::table('t_stok')
                ->where('barang_id', $barang->barang_id)
                ->decrement('stok_jumlah', $request->jumlah);
        });
        
        return response()->json([
            'status'  => true,
            'message' => 'Data detail penjualan berhasil disimpan'
        ]);        
    }

    public function confirm_ajax($id)
    {
        $detail = PenjualanDetailModel::with(['barang', 'penjualan'])->findOrFail($id);
        return view('penjualan_detail.confirm_ajax', ['PenjualanDetail' => $detail]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if (! $request->ajax()) {
            return redirect('/penjualan_detail');
        }

        $detail = PenjualanDetailModel::find($id);
        if (! $detail) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
        }

        // **(Optional)** Jika ingin mengembalikan stok ketika hapus detail, aktifkan blok ini:
        // DB::table('t_stok')
        //   ->where('barang_id', $detail->barang_id)
        //   ->increment('stok_jumlah', $detail->jumlah);

        $detail->delete();
        return response()->json(['status' => true, 'message' => 'Data berhasil dihapus']);
    }

    public function export_excel()
    {
        $penjualanDetails = PenjualanDetailModel::select('penjualan_id', 'barang_id', 'harga', 'jumlah', 'total_harga', 'metode_pembayaran')
            ->with(['penjualan', 'barang'])
            ->orderBy('penjualan_id')
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Penjualan');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Harga Per Barang');
        $sheet->setCellValue('E1', 'Jumlah Barang');
        $sheet->setCellValue('F1', 'Total Harga');
        $sheet->setCellValue('G1', 'Metode Pembayaran');

        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

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

        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Transaksi');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_Transaksi_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
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

        $pdf = Pdf::loadView('penjualan_detail.export_pdf', [
            'PenjualanDetail' => $PenjualanDetail
        ]);

        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data_Transaksi_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
