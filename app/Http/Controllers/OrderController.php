<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Cart;

use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class OrderController extends Controller
{
    private $apiContext;

    public function __construct()
    {
        $paypalConfig = [
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'client_secret' => env('PAYPAL_CLIENT_SECRET'),
            'settings' => [
                'mode' => env('PAYPAL_MODE', 'sandbox'),
                'http.ConnectionTimeOut' => 30,
                'log.LogEnabled' => true,
            ],
        ];
        
       // $paypalConfig = config('paypal');
        $this->apiContext = new ApiContext(new OAuthTokenCredential(
            $paypalConfig['client_id'],
            $paypalConfig['client_secret']
        ));
        $this->apiContext->setConfig($paypalConfig['settings']);
    }

    public function checkout()
    { //suppose user with id 1 is logged in.
        
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

        return view('checkout', compact('cart','user','cartItems','productsInCart','cartItemCount'));
    }

    public function placeOrder(Request $request)
    {
        $id =1;
        $order = Order::create($request->all());
        $cartItems = Cart::where('user_id', $id)->get();

        // Extract product IDs from cart items
        $productIds = $cartItems->pluck('product_id')->toArray();

        // Retrieve details of products associated with the cart items
        $productsInCart = Product::whereIn('id', $productIds)->get();
        $totalAmount = 0;
        foreach ($productsInCart as $product){
            $totalAmount += $product->price;
        }
        // Create payment
        $payment = new Payment();
        $payment->setIntent('sale');

        $amount = new Amount();
        $amount->setTotal($totalAmount); // Change this value to the total amount of the order
        $amount->setCurrency('USD');

        $transaction = new Transaction();
        $transaction->setAmount($amount);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('payment.success'));
        $redirectUrls->setCancelUrl(route('payment.cancel'));

        $payment->setRedirectUrls($redirectUrls);
        $payment->setTransactions([$transaction]);

        try {
            $payment->create($this->apiContext);
            return redirect($payment->getApprovalLink());
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Payment initiation failed.']);
        }
    }

    public function paymentSuccess()
    {
        return view('thankyou');
    }

    public function paymentFailed()
    {
        return view('thankyou');
    }
}
