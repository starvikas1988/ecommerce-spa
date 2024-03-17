<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $id =   $this->params['id'];
        $user = User::find($id);
        return view('products.index', compact('user'));
    }
}
