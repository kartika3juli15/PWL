<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\ItemController; //impor ItemController agar bisa digunakan dalam routing
use Illuminate\Support\Facades\Route; //impor Route untuk mendefinisikan rute dalam aplikasi Laravel
use App\Http\Controllers\Master\CityController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\WelcomeController;

//definisi rute halaman utama yang akan menampilkan tampilan welcome.blade.php
Route::get('/', function () {
    return view('welcome');
});

Route::resource('items', ItemController::class); //mendaftarkan semua rute CRUD

Route::get('/hello', function () {
    return 'Hello World';
});

Route::get('/world', function () { 
    return 'World';
});

Route::get('/about', function () { 
    return 'Selamat Datang';
});

//Route::get('/user/{name}', function ($tika) { 
  //  return 'Nama saya '.$tika;
//});

Route::get('/posts/{post}/comments/{comment}', function ($postId, $commentId) {
    return 'Pos ke-'.$postId." Komentar ke-: ".$commentId;
    });
    

Route::get('/articles/{id}', function ($id) {
    return 'Halaman Artikel dengan ID '.$id;
});

Route::get('/user/{name?}', function ($name='Kartika') {
    return 'Nama saya '.$name;
});

Route::get('/user/profile', function () {
    //
    })->name('profile');
    
    Route::get(
    '/user/profile', [UserProfileController::class, 'show']
    )->name('profile');
    
    
//-	Route Name
Route::get('/user/profile', function () {
//
})->name('profile');

Route::get(
'/user/profile', [UserProfileController::class, 'show']
)->name('profile');

// Generating URLs...
// $url = route('profile');

// Generating Redirects...
// return redirect()->route('profile');


//-	Route Group dan Route Prefixes
Route::middleware(['first', 'second'])->group(function () { Route::get('/', function () {
    // Uses first & second middleware...
    });
    
    Route::get('/user/profile', function () {
    // Uses first & second middleware...
    });
    });
    
    Route::domain('{account}.example.com')->group(function () { 
        Route::get('user/{id}', function ($account, $id) {
    //
    });
    });
    
    Route::middleware('auth')->group(function () { 
        Route::get('/user', [UserController::class, 'index']); 
        Route::get('/post', [PostController::class, 'index']); 
        Route::get('/event', [EventController::class, 'index']);
    });
    
//Route Prefix
    Route::prefix('admin')->group(function () { 
        Route::get('/user', [UserController::class, 'index']); 
        Route::get('/post', [PostController::class, 'index']); 
        Route::get('/event', [EventController::class, 'index']);

    });
    
//Redirect Route
Route::redirect('/here', '/there');

//view
Route::view('/welcome', 'welcome'); 
Route::view('/welcome', 'welcome', ['name' => 'Taylor']);

Route::get('/', [PageController::class,'index']);
Route::get('/about', [PageController::class,'about']);
Route::get('/articles/{id}', [PageController::class,'articles']);


Route::get('/', HomeController::class);
Route::get('/about', AboutController::class);
Route::get('/articles/{id}', ArticleController::class);

Route::get('/greeting', function () {
    return view('blog.hello', ['name' => 'Tika']);
    });
    
Route::get('/greeting', [WelcomeController::class, 'greeting']);
