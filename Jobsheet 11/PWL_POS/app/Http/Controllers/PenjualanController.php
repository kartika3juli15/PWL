<?php

namespace App\Http\Controllers;

use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use App\Models\UserModel;
use App\Models\StokModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanController extends Controller
{
    public function index()
    {
        $activeMenu = 'penjualan';
        $breadcrumb = (object)[
            'title' => 'Data Penjualan',
            'list' => ['Home', 'Penjualan']
        ];
        $users = UserModel::select('user_id', 'username')->get();
        return view('penjualan.index', compact('activeMenu', 'breadcrumb', 'users'));
    }

    public function list(Request $request)
    {
        $penjualan = PenjualanModel::with('user')
            ->select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal');

        if ($request->filled('filter_user')) {
            $penjualan->where('user_id', $request->filter_user);
        }

        return DataTables::of($penjualan)
            ->addIndexColumn()
            ->addColumn('user_nama', function ($p) {
                return $p->user->username ?? '-';
            })
            ->addColumn('aksi', function ($p) {
                $btn = '';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $p->penjualan_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $p->penjualan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        return view('penjualan.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'pembeli' => 'required|string|max:50',
                'penjualan_kode' => 'required|string|max:20|unique:t_penjualan,penjualan_kode',
                'penjualan_tanggal' => 'required|date',
                'barang' => 'required|array|min:1',
                'barang.*.stok_id' => 'required|exists:t_stok,stok_id',
                'barang.*.jumlah' => 'required|numeric|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            DB::beginTransaction();
            try {
                $penjualan = PenjualanModel::create([
                    'user_id' => Auth::user()->user_id,
                    'pembeli' => $request->pembeli,
                    'penjualan_kode' => $request->penjualan_kode,
                    'penjualan_tanggal' => $request->penjualan_tanggal,
                ]);

                foreach ($request->barang as $item) {
                    $stok = StokModel::find($item['stok_id']);

                    if ($stok->stok_jumlah < $item['jumlah']) {
                        DB::rollBack();
                        return response()->json([
                            'status' => false,
                            'message' => 'Stok tidak mencukupi untuk salah satu item.'
                        ]);
                    }

                    $stok->stok_jumlah -= $item['jumlah'];
                    $stok->save();

                    PenjualanDetailModel::create([
                        'penjualan_id' => $penjualan->penjualan_id,
                        'stok_id' => $item['stok_id'],
                        'jumlah' => $item['jumlah'],
                    ]);
                }

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil disimpan dan stok diperbarui'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage()
                ]);
            }
        }

        return redirect('/');
    }

    public function edit_ajax($id)
    {
        $penjualan = PenjualanModel::findOrFail($id);
        return view('penjualan.edit_ajax', compact('penjualan'));
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'pembeli' => 'required|string|max:50',
                'penjualan_kode' => 'required|string|max:20|unique:t_penjualan,penjualan_kode,' . $id . ',penjualan_id',
                'penjualan_tanggal' => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'msgField' => $validator->errors()
                ]);
            }

            $penjualan = PenjualanModel::findOrFail($id);
            $penjualan->update([
                'pembeli' => $request->pembeli,
                'penjualan_kode' => $request->penjualan_kode,
                'penjualan_tanggal' => $request->penjualan_tanggal,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate'
            ]);
        }

        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $penjualan = PenjualanModel::findOrFail($id);
        return view('penjualan.confirm_ajax', compact('penjualan'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax()) {
            $penjualan = PenjualanModel::find($id);

            if ($penjualan) {
                $penjualan->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/penjualan');
    }

    public function export_excel()
    {
        $penjualan = PenjualanModel::select('user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
            ->orderBy('user_id')
            ->with('user')
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Penjualan');
        $sheet->setCellValue('C1', 'Tanggal Penjualan');
        $sheet->setCellValue('D1', 'Pembeli');
        $sheet->setCellValue('E1', 'Pegawai');
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;
        foreach ($penjualan as $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->penjualan_kode);
            $sheet->setCellValue('C' . $baris, $value->penjualan_tanggal);
            $sheet->setCellValue('D' . $baris, $value->pembeli);
            $sheet->setCellValue('E' . $baris, $value->user->username ?? '-');

            $baris++;
            $no++;
        }

        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Penjualan');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_Penjualan_' . date('Y-m-d H:i:s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $penjualan = PenjualanModel::select('user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
            ->orderBy('user_id')
            ->orderBy('penjualan_kode')
            ->with('user')
            ->get();

        $pdf = Pdf::loadView('penjualan.export_pdf', ['penjualan' => $penjualan]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data_Penjualan_' . date('Y-m-d H:i:s') . '.pdf');
    }
}
