<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Cart;

class ProductController extends Controller
{
    public function index()
    { //suppose user with id 1 is logged in.
        $products = Product::all();
        $id =   1;
        $user = User::find($id);

        $cartItemCount = Cart::where('user_id', $id)->count();

       // dd($user['name']);
        return view('products.index', compact('products', 'id', 'user', 'cartItemCount'));
    }
}
