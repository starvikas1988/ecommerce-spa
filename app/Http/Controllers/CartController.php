<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\User;
class CartController extends Controller
{
    /**
     * Display the cart.
     *
     * @return \Illuminate\Http\Response
     */
    public function startpoint()
    {
        // Retrieve cart items from session
        $cart = session()->get('cart');

        $id =   1;
        $user = User::find($id);

        $cartItemCount = Cart::where('user_id', $id)->count();

         // Retrieve cart items for the user
        $cartItems = Cart::where('user_id', $id)->get();

        // Extract product IDs from cart items
        $productIds = $cartItems->pluck('product_id')->toArray();

        // Retrieve details of products associated with the cart items
        $productsInCart = Product::whereIn('id', $productIds)->get();
        //dd($productsInCart);

        return view('products.cart', compact('cart','user','cartItems','productsInCart','cartItemCount'));
    }

    /**
     * Add a product to the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function add(Request $request)
    // {
    //     $product = Product::findOrFail($request->product_id);

    //     // Add product to cart in session
    //     $cart = session()->get('cart', []);

    //     if(isset($cart[$product->id])) {
    //         $cart[$product->id]['quantity']++;
    //     } else {
    //         $cart[$product->id] = [
    //             'name' => $product->name,
    //             'quantity' => 1,
    //             'price' => $product->price,
    //         ];
    //     }

    //     session()->put('cart', $cart);

    //     return redirect()->route('products.cart')->with('success', 'Product added to cart successfully.');
    // }

    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1); // Default quantity is 1

        // Find the product
        $product = Product::findOrFail($productId);

        // Check if the product is already in the cart
        $existingCartItem = Cart::where('product_id', $productId)
                                ->where('user_id', 1)
                                ->first();

        if ($existingCartItem) {
            // Update the quantity if the product is already in the cart
            $existingCartItem->quantity += $quantity;
            $existingCartItem->save();
        } else {
            // Create a new cart item
            $cartItem = new Cart();
            $cartItem->user_id = 1;
            $cartItem->product_id = $productId;
            $cartItem->quantity = $quantity;
            $cartItem->save();
        }

        return response()->json(['success' => 'Product added to cart successfully.']);
    }

    /**
     * Remove a product from the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
       // dd($request);
        $productId = $request->product_id;
       // $userId = auth()->id(); // Assuming you are using authentication
       $userId = $request->user_id;

        $cart = session()->get('cart');

        if(isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }
        // Find and delete the cart item
        $cartItem = Cart::where('user_id', $userId)
                        ->where('product_id', $productId)
                        ->first();

        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['success' => 'Product removed from cart successfully.']);
        } else {
            return response()->json(['error' => 'Product not found in cart.'], 404);
        }
    }

    /**
     * Clear the cart.
     *
     * @return \Illuminate\Http\Response
     */
    public function clear()
    {
        // Clear cart in session
        session()->forget('cart');

        return redirect()->route('products.cart')->with('success', 'Cart cleared successfully.');
    }
}
