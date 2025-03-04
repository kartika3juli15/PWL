<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;

// Generating URLs...
    $url = route('profile'); 
    
// Generating Redirects...
    return redirect()->route('profile');

class WelcomeController extends Controller
{
public function hello() {
return 'Hello World';
}


}

Route::get('/hello', [WelcomeController::class,'hello']);

