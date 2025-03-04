<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AboutController extends Controller
{
    public function __invoke(Request $request){
        return 'Nama : [isi nama] <br> NIM : [isi NIM]';
    }
}
