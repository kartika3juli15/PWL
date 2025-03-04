<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showProfile($id, $name)
    {
        return view('user.profile', ['id' => $id, 'name' => $name]);
    }
}
