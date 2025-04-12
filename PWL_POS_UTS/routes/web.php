<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PenjualanDetailController;
use App\Models\PenjualanDetailModel;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//router log out
Route::get('/logout-confirm', function () {
    return view('auth.logout'); 
})->name('logout.confirm');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//router register
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');


Route::get('/', function () {
    return view('welcome');
});

Route::get('/level', [LevelController::class, 'index']);
Route::get('/kategori', [KategoriController::class, 'index']);
Route::get('/user', [UserController::class, 'index']);

Route::get('/user/tambah', [UserController::class, 'tambah']);
Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);

Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);
Route::post('/user/ubah_simpan', [UserController::class, 'ubah_simpan']);
Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']);
Route::get('/user/hapus/{id}', [UserController::class, 'hapus']);


//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', [WelcomeController::class, 'index']);

Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']);                              // menampilkan halaman awal user
    Route::post('/list', [UserController::class, 'list']);                          // menampilkan data user dalam bentuk json untuk datatables
    Route::get('/create', [UserController::class, 'create']);                       // menampilkan halaman form tambah user
    Route::post('/', [UserController::class, 'store']);                             // menyimpan data user baru
    Route::get('/create_ajax', [UserController::class, 'create_ajax']);             // menampilkan halaman form tambah user Ajax
    Route::post('/ajax', [UserController::class, 'store_ajax']);                    // menyimpan data user baru Ajax
    Route::get('/{id}', [UserController::class, 'show']);                           // menampilkan detail user
    Route::get('/{id}/edit', [UserController::class, 'edit']);                      // menampilkan halaman form edit user
    Route::put('/{id}', [UserController::class, 'update']);                         // menyimpan perubahan data user
    Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);            // menampilkan halaman form edit user AJax
    Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);        // menyimpan perubahan data user Ajax
    Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);       // untuk tampilkan form confirm delete user Ajax
    Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']);     // untuk hapus data user Ajax
    Route::delete('/{id}', [UserController::class, 'destroy']);                     // menghapus data user
});

Route::group(['prefix' => 'level'], function(){
    Route::get('/', [LevelController::class, 'index']); // menampilkan halaman awal level
    Route::post('/list', [LevelController::class, 'list']); // menampilkan data level dalam bentuk json untuk datables
    Route::get('/create', [LevelController::class, 'create']); // menampilkan halaman form tambah level
    Route::get('/create_ajax', [LevelController::class, 'create_ajax']); // menampilkan halaman form tambah level Ajax
    Route::post('/ajax', [LevelController::class, 'store_ajax']); // menyimpan data level baru Ajax
    Route::post('/', [LevelController::class, 'store']); // menyimpan data level baru
    Route::get('/{id}', [LevelController::class, 'show']); // menampilkan detail level
    Route::get('/{id}/edit', [LevelController::class, 'edit']); // menampilkan halaman form edit level
    Route::put('/{id}', [LevelController::class, 'update']); // menyimpan data level yang diubah
    Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']); // menampilkan halaman form edit data level Ajax
    Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']); // menyimpan perubahan data level Ajax
    Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']); // untuk tampilkan form confirm delete level Ajax
    Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); // untuk hapus data level Ajax
    Route::delete('/{id}', [LevelController::class, 'destroy']); // menghapus data level
});

Route::group(['prefix' => 'kategori'], function(){
    Route::get('/', [KategoriController::class, 'index']); // menampilkan halaman awal kategori
    Route::post('/list', [KategoriController::class, 'list']); // menampilkan data kategori dalam bentuk json untuk datables
    Route::get('/create', [KategoriController::class, 'create']); // menampilkan halaman form tambah kategori
    Route::get('/create_ajax', [KategoriController::class, 'create_ajax']); // menampilkan halaman form tambah kategori Ajax
    Route::post('/ajax', [KategoriController::class, 'store_ajax']); // menyimpan data kategori baru Ajax
    Route::post('/', [KategoriController::class, 'store']); // menyimpan data kategori baru
    Route::get('/{id}', [KategoriController::class, 'show']); // menampilkan detail kategori
    Route::get('/{id}/edit', [KategoriController::class, 'edit']); // menampilkan halaman form edit kategori
    Route::put('/{id}', [KategoriController::class, 'update']); // menyimpan data kategori yang diubah
    Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']); // menampilkan halaman form edit data kategori Ajax
    Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']); // menyimpan perubahan data kategori Ajax
    Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']); // untuk tampilkan form confirm delete kategori Ajax
    Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); // untuk hapus data kategori Ajax
    Route::delete('/{id}', [KategoriController::class, 'destroy']); // menghapus data kategori
});

Route::group(['prefix' => 'supplier'], function(){
    Route::get('/', [SupplierController::class, 'index']); // menampilkan halaman awal supplier
    Route::post('/list', [SupplierController::class, 'list']); // menampilkan data supplier dalam bentuk json untuk datables
    Route::get('/create', [SupplierController::class, 'create']); // menampilkan halaman form tambah supplier
    Route::get('/create_ajax', [SupplierController::class, 'create_ajax']); // menampilkan halaman form tambah supplier Ajax
    Route::post('/ajax', [SupplierController::class, 'store_ajax']); // menyimpan data supplier baru Ajax
    Route::post('/', [SupplierController::class, 'store']); // menyimpan data supplier baru
    Route::get('/{id}', [SupplierController::class, 'show']); // menampilkan detail supplier
    Route::get('/{id}/edit', [SupplierController::class, 'edit']); // menampilkan halaman form edit supplier
    Route::put('/{id}', [SupplierController::class, 'update']); // menyimpan data supplier yang diubah
    Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']); // menampilkan halaman form edit data supplier Ajax
    Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']); // menyimpan perubahan data supplier Ajax
    Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']); // untuk tampilkan form confirm delete supplier Ajax
    Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); // untuk hapus data supplier Ajax
    Route::delete('/{id}', [SupplierController::class, 'destroy']); // menghapus data supplier
});

Route::group(['prefix' => 'barang'], function () {
    Route::get('/', [BarangController::class, 'index']);
    Route::post('/list', [BarangController::class, 'list']);
    Route::get('/create', [BarangController::class, 'create']);
    Route::post('/', [BarangController::class, 'store']);
    Route::get('/create_ajax', [BarangController::class, 'create_ajax']);
    Route::post('/ajax', [BarangController::class, 'store_ajax']);
    Route::get('/{id}', [BarangController::class, 'show']);
    Route::get('/{id}/edit', [BarangController::class, 'edit']);
    Route::put('/{id}', [BarangController::class, 'update']);
    Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);
    Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']);
    Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);
    Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']);
    Route::get('/import', [BarangController::class, 'import']); // ajax form upload excel
    Route::post('/import_ajax', [BarangController::class, 'import_ajax']); // ajax import excel
    Route::delete('/{id}', [BarangController::class, 'destroy']);
});

Route::group(['prefix' => 'stok'], function () {
    Route::get('/', [StokController::class, 'index']);                              // menampilkan halaman awal stok   
    Route::post('/list', [StokController::class, 'list']);                          // menampilkan data stok dalam bentuk json untuk datatables   
    Route::get('/create', [StokController::class, 'create']);                       // menampilkan halaman form tambah stok
    Route::post('/', [StokController::class, 'store']);                             // menyimpan data stok baru
    Route::get('/create_ajax', [StokController::class, 'create_ajax']);             // menampilkan halaman form tambah user Ajax             
    Route::post('/ajax', [StokController::class, 'store_ajax']);                    // menyimpan data user baru Ajax   
    Route::get('/{id}', [StokController::class, 'show']);                           // menampilkan detail stok  
    Route::get('/{id}/edit', [StokController::class, 'edit']);                      // menampilkan halaman form edit stok
    Route::put('/{id}', [StokController::class, 'update']);                         // menyimpan perubahan data stok 
    Route::get('/{id}/edit_ajax', [StokController::class, 'edit_ajax']);            // Menampilkan halaman form edit user Ajax           
    Route::put('/{id}/update_ajax', [StokController::class, 'update_ajax']);        // Menyimpan perubahan data user Ajax   
    Route::get('/{id}/delete_ajax', [StokController::class, 'confirm_ajax']);       // Untuk tampilkan form confirm delete user Ajax  
    Route::delete('/{id}/delete_ajax', [StokController::class, 'delete_ajax']);     // Untuk hapus data user Ajax   
    Route::delete('/{id}', [StokController::class, 'destroy']);                      // menghapus data stok
});

Route::pattern('id', '[0-9]+'); // artinya ketika ada parameter {id}, maka harus berupa angka

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('logout');

Route::middleware(['auth'])->group(function () { // artinya semua route di dalam group ini harus login dulu
    Route::get('/', [WelcomeController::class, 'index']);

    // masukkan semua route yang perlu autentikasi di sini

    Route::group(['prefix' => 'user'], function () {
        Route::middleware(['authorize:ADM,MNG'])->group(function () {
            Route::get('/', [UserController::class, 'index']);                              // menampilkan halaman awal user
            Route::post('/list', [UserController::class, 'list']);                          // menampilkan data user dalam bentuk json untuk datatables
            Route::get('/create', [UserController::class, 'create']);                       // menampilkan halaman form tambah user
            Route::post('/', [UserController::class, 'store']);                             // menyimpan data user baru
            Route::get('/create_ajax', [UserController::class, 'create_ajax']);             // menampilkan halaman form tambah user Ajax
            Route::post('/ajax', [UserController::class, 'store_ajax']);                    // menyimpan data user baru Ajax
            Route::get('/{id}', [UserController::class, 'show']);                           // menampilkan detail user
            Route::get('/{id}/edit', [UserController::class, 'edit']);                      // menampilkan halaman form edit user
            Route::put('/{id}', [UserController::class, 'update']);                         // menyimpan perubahan data user
            Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);            // menampilkan halaman form edit user AJax
            Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);        // menyimpan perubahan data user Ajax
            Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);       // untuk tampilkan form confirm delete user Ajax
            Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']);     // untuk hapus data user Ajax
            Route::delete('/{id}', [UserController::class, 'destroy']);                     // menghapus data user
            Route::get('/export_excel', [UserController::class, 'export_excel']);          // export excel
            Route::get('/export_pdf', [UserController::class, 'export_pdf']);              // export pdf
        });
    });

    Route::group(['prefix' => 'level'], function () {
        Route::middleware(['authorize:ADM'])->group(function () {
            Route::get('/', [LevelController::class, 'index']);                             // Menampilkan halaman awal level user
            Route::post('/list', [LevelController::class, 'list']);                         // menampilkan level user dalam bentuk json untuk datatables
            Route::get('/create', [LevelController::class, 'create']);                      // menampilkan halaman form tambah level user
            Route::post('/', [LevelController::class, 'store']);                            // menyimpan level user baru   
            Route::get('/create_ajax', [LevelController::class, 'create_ajax']);            // menampilkan halaman form tambah user Ajax     
            Route::post('/ajax', [LevelController::class, 'store_ajax']);                   // menyimpan data user baru Ajax
            Route::get('/{id}', [LevelController::class, 'show']);                          // Menampilkan detail level user
            Route::get('/{id}/edit', [LevelController::class, 'edit']);                     // menampilkan halaman form edit level user
            Route::put('/{id}', [LevelController::class, 'update']);                        // menyimpan perubahan level user
            Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);           // Menampilkan halaman form edit user Ajax            
            Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']);       // Menyimpan perubahan data user Ajax      
            Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);      // Untuk tampilkan form confirm delete user Ajax   
            Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']);    // Untuk hapus data user Ajax
            Route::get('/import', [LevelController::class, 'import']);                      // ajax form upload excel
            Route::post('/import_ajax', [LevelController::class, 'import_ajax']);           // ajax import excel
            Route::delete('/{id}', [LevelController::class, 'destroy']);                    // menghapus level user
            Route::get('/export_excel', [LevelController::class, 'export_excel']);          // export excel
            Route::get('/export_pdf', [LevelController::class, 'export_pdf']);              // export pdf
        });
    });

    Route::group(['prefix' => 'kategori'], function () {
        Route::middleware(['authorize:ADM,MNG,STF'])->group(function () {
            Route::get('/', [KategoriController::class, 'index']);                          // Menampilkan halaman awal daftar kategori   
            Route::post('/list', [KategoriController::class, 'list']);                      // menampilkan kategori dalam bentuk json untuk datatables
            Route::get('/create', [KategoriController::class, 'create']);                   // menampilkan halaman form tambah kategori 
            Route::post('/', [KategoriController::class, 'store']);                         // menyimpan kategori baru
            Route::get('/create_ajax', [KategoriController::class, 'create_ajax']);         // menampilkan halaman form tambah user Ajax          
            Route::post('/ajax', [KategoriController::class, 'store_ajax']);                // menyimpan data user baru Ajax  
            Route::get('/{id}', [KategoriController::class, 'show']);                       // menampilkan detail kategori    
            Route::get('/{id}/edit', [KategoriController::class, 'edit']);                  // menampilkan halaman form edit kategori
            Route::put('/{id}', [KategoriController::class, 'update']);                     // menyimpan perubahan kategori
            Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']);        // Menampilkan halaman form edit user Ajax         
            Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']);    // Menyimpan perubahan data user Ajax
            Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']);   // Untuk tampilkan form confirm delete user Ajax      
            Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); // Untuk hapus data user Ajax    
            Route::get('/import', [KategoriController::class, 'import']);                   // ajax form upload excel
            Route::post('/import_ajax', [KategoriController::class, 'import_ajax']);        // ajax import excel    
            Route::delete('/{id}', [KategoriController::class, 'destroy']);                 // menghapus kategori
            Route::get('/export_excel', [KategoriController::class, 'export_excel']);       // export excel
            Route::get('/export_pdf', [KategoriController::class, 'export_pdf']);           // export pdf
        });
    });

    Route::group(['prefix' => 'supplier'], function(){
        Route::middleware(['authorize:ADM,MNG'])->group(function () {
        Route::get('/', [SupplierController::class, 'index']); // menampilkan halaman awal supplier
        Route::post('/list', [SupplierController::class, 'list']); // menampilkan data supplier dalam bentuk json untuk datables
        Route::get('/create', [SupplierController::class, 'create']); // menampilkan halaman form tambah supplier
        Route::get('/create_ajax', [SupplierController::class, 'create_ajax']); // menampilkan halaman form tambah supplier Ajax
        Route::post('/ajax', [SupplierController::class, 'store_ajax']); // menyimpan data supplier baru Ajax
        Route::post('/', [SupplierController::class, 'store']); // menyimpan data supplier baru
        Route::get('/{id}', [SupplierController::class, 'show']); // menampilkan detail supplier
        Route::get('/{id}/edit', [SupplierController::class, 'edit']); // menampilkan halaman form edit supplier
        Route::put('/{id}', [SupplierController::class, 'update']); // menyimpan data supplier yang diubah
        Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']); // menampilkan halaman form edit data supplier Ajax
        Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']); // menyimpan perubahan data supplier Ajax
        Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']); // untuk tampilkan form confirm delete supplier Ajax
        Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); // untuk hapus data supplier Ajax
        Route::get('/import', [SupplierController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [SupplierController::class, 'import_ajax']); // ajax import excel 
        Route::delete('/{id}', [SupplierController::class, 'destroy']); // menghapus data supplier
        Route::get('/export_excel', [SupplierController::class, 'export_excel']); // export excel
        Route::get('/export_pdf', [SupplierController::class, 'export_pdf']); // export pdf
        });
    });
    

    Route::group(['prefix' => 'barang'], function () {
        Route::middleware(['authorize:ADM,MNG,STF'])->group(function () {
            Route::get('/', [BarangController::class, 'index']);                                // menampilkan halaman awal barang      
            Route::post('/list', [BarangController::class, 'list']);                            // menampilkan data barang dalam bentuk json untuk datatables    
            Route::get('/create', [BarangController::class, 'create']);                         // menampilkan halaman form tambah barang 
            Route::post('/', [BarangController::class, 'store']);                               // menyimpan data barang baru
            Route::get('/create_ajax', [BarangController::class, 'create_ajax']);               // menampilkan halaman form tambah user Ajax            
            Route::post('/ajax', [BarangController::class, 'store_ajax']);                      // menyimpan data user baru Ajax
            Route::get('/{id}', [BarangController::class, 'show']);                             // menampilkan detail barang
            Route::get('/{id}/edit', [BarangController::class, 'edit']);                        // menampilkan halaman form edit barang
            Route::put('/{id}', [BarangController::class, 'update']);                           // menyimpan perubahan data barang
            Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);              // Menampilkan halaman form edit user Ajax          
            Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']);          // Menyimpan perubahan data user Ajax     
            Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);         // Untuk tampilkan form confirm delete user Ajax      
            Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']);       // Untuk hapus data user Ajax   
            Route::get('/import', [BarangController::class, 'import']);                         // ajax form upload excel
            Route::post('/import_ajax', [BarangController::class, 'import_ajax']);              // ajax import excel
            Route::delete('/{id}', [BarangController::class, 'destroy']);                       // menghapus data barang
            Route::get('/export_excel', [BarangController::class, 'export_excel']);             // export excel
            Route::get('/export_pdf', [BarangController::class, 'export_pdf']);                 // export pdf
        });
    });

    Route::group(['prefix' => 'stok'], function () {
        Route::middleware(['authorize:MNG,ADM,STF'])->group(function () {
            Route::get('/', [StokController::class, 'index']);                              // menampilkan halaman awal stok   
            Route::post('/list', [StokController::class, 'list']);                          // menampilkan data stok dalam bentuk json untuk datatables   
            Route::get('/create', [StokController::class, 'create']);                       // menampilkan halaman form tambah stok
            Route::post('/', [StokController::class, 'store']);                             // menyimpan data stok baru
            Route::get('/create_ajax', [StokController::class, 'create_ajax']);             // menampilkan halaman form tambah user Ajax             
            Route::post('/ajax', [StokController::class, 'store_ajax']);                    // menyimpan data user baru Ajax   
            Route::get('/{id}', [StokController::class, 'show']);                           // menampilkan detail stok  
            Route::get('/{id}/edit', [StokController::class, 'edit']);                      // menampilkan halaman form edit stok
            Route::put('/{id}', [StokController::class, 'update']);                         // menyimpan perubahan data stok 
            Route::get('/{id}/edit_ajax', [StokController::class, 'edit_ajax']);            // Menampilkan halaman form edit user Ajax           
            Route::put('/{id}/update_ajax', [StokController::class, 'update_ajax']);        // Menyimpan perubahan data user Ajax   
            Route::get('/{id}/delete_ajax', [StokController::class, 'confirm_ajax']);       // Untuk tampilkan form confirm delete user Ajax  
            Route::delete('/{id}/delete_ajax', [StokController::class, 'delete_ajax']);     // Untuk hapus data user Ajax   
            Route::delete('/{id}', [StokController::class, 'destroy']);                     // menghapus data stok
        });                   
    });

    Route::group(['prefix' => 'penjualan'], function () {
        Route::middleware(['authorize:ADM,MNG'])->group(function () {
            Route::get('/', [PenjualanController::class, 'index']);                              // menampilkan halaman awal user
            Route::post('/list', [PenjualanController::class, 'list']);                          // menampilkan data user dalam bentuk json untuk datatables
            Route::get('/create', [PenjualanController::class, 'create']);                       // menampilkan halaman form tambah user
            Route::post('/', [PenjualanController::class, 'store']);                             // menyimpan data user baru
            Route::get('/create_ajax', [PenjualanController::class, 'create_ajax']);             // menampilkan halaman form tambah user Ajax
            Route::post('/ajax', [PenjualanController::class, 'store_ajax']);                    // menyimpan data user baru Ajax
            Route::get('/{id}', [PenjualanController::class, 'show']);                           // menampilkan detail user
            Route::get('/{id}/edit', [PenjualanController::class, 'edit']);                      // menampilkan halaman form edit user
            Route::put('/{id}', [PenjualanController::class, 'update']);                         // menyimpan perubahan data user
            Route::get('/{id}/edit_ajax', [PenjualanController::class, 'edit_ajax']);            // menampilkan halaman form edit user AJax
            Route::put('/{id}/update_ajax', [PenjualanController::class, 'update_ajax']);        // menyimpan perubahan data user Ajax
            Route::get('/{id}/delete_ajax', [PenjualanController::class, 'confirm_ajax']);       // untuk tampilkan form confirm delete user Ajax
            Route::delete('/{id}/delete_ajax', [PenjualanController::class, 'delete_ajax']);     // untuk hapus data user Ajax
            Route::delete('/{id}', [PenjualanController::class, 'destroy']);                     // menghapus data user
            Route::get('/export_excel', [PenjualanController::class, 'export_excel']);          // export excel
            Route::get('/export_pdf', [PenjualanController::class, 'export_pdf']);              // export pdf
        });
    });

    Route::group(['prefix' => 'penjualan_detail'], function () {
        Route::middleware(['authorize:ADM,MNG'])->group(function () {
            Route::get('/', [PenjualanDetailController::class, 'index']);                              // menampilkan halaman awal user
            Route::post('/list', [PenjualanDetailController::class, 'list']);                          // menampilkan data user dalam bentuk json untuk datatables
            Route::get('/create', [PenjualanDetailController::class, 'create']);                       // menampilkan halaman form tambah user
            Route::post('/', [PenjualanDetailController::class, 'store']);                             // menyimpan data user baru
            Route::get('/create_ajax', [PenjualanDetailController::class, 'create_ajax']);             // menampilkan halaman form tambah user Ajax
            Route::post('/ajax', [PenjualanDetailController::class, 'store_ajax']);                    // menyimpan data user baru Ajax
            Route::get('/{id}', [PenjualanDetailController::class, 'show']);                           // menampilkan detail user
            Route::get('/{id}/edit', [PenjualanDetailController::class, 'edit']);                      // menampilkan halaman form edit user
            Route::put('/{id}', [PenjualanDetailController::class, 'update']);                         // menyimpan perubahan data user
            Route::get('/{id}/edit_ajax', [PenjualanDetailController::class, 'edit_ajax']);            // menampilkan halaman form edit user AJax
            Route::put('/{id}/update_ajax', [PenjualanDetailController::class, 'update_ajax']);        // menyimpan perubahan data user Ajax
            Route::get('/{id}/delete_ajax', [PenjualanDetailController::class, 'confirm_ajax']);       // untuk tampilkan form confirm delete user Ajax
            Route::delete('/{id}/delete_ajax', [PenjualanDetailController::class, 'delete_ajax']);     // untuk hapus data user Ajax
            Route::delete('/{id}', [PenjualanDetailController::class, 'destroy']);                     // menghapus data user
            Route::get('/export_excel', [PenjualanDetailController::class, 'export_excel']);          // export excel
            Route::get('/export_pdf', [PenjualanDetailController::class, 'export_pdf']);              // export pdf
            Route::get('/get_harga_barang/{id}', [PenjualanDetailController::class, 'getHargaBarang']);
            Route::post('/store_ajax', [PenjualanDetailController::class, 'store_ajax'])->name('penjualan_detail.store_ajax');
        });
    });
});


