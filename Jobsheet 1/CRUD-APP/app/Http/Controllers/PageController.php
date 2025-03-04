<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PageController extends Controller { 
public function index(){
    return "Selamat Datang";
}

public function about(){
    return "Nama : Kartika Tri Juliana <br> NIM : 2341760116";
}

public function articles($id){
    return "Halaman Artikel dengan ID ".$id;
}
}

