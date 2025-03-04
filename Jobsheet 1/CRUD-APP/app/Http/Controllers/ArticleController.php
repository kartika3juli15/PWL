<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ArticleController extends Controller
{
    public function __invoke(Request $request){
        $id = $request -> input('id');
        return 'Halaman Artikel dengan ID'.$id;
    }
}
