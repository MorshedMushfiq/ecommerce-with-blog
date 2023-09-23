<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
 


    public function requestPaypal(Request $request){

        $provider = new PayPalCLient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $amount = $request->amount;
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                'return_url' => route('payment.success'),
                'cancel_url' => route('payment.cancel'),
            ],
            "purchase_units" => [
                0 => [
                    'amount' =>[
                        'currency_code' => 'USD',
                        'value' => $request->bill,
                    ],
    
                ],
            ],
    
        ]);
    
        if(isset($response['id']) && $response['id'] != null){
            //redirect to approve href
    
            foreach($response['links'] as $links){
                if($links['rel']=='approve'){
                    
                     return redirect()->away($links['href']);
                }
            }
            return redirect()->route('shopping.cart')->with('error', "something went wrong, please try again later");
        }else{
            return redirect()->route('shopping.cart')->with('error', $response['message']?? "something went erong....");
        }
    
    }
    
    public function paymentSuccess(Request $request){
    
        $provider = new PayPalCLient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);
        if(isset($response['status']) && $response['status']=="COMPLETED"){
            $order = new Order;
            $order->status = "Paid";
            $order->customerId = Auth::user()->id;
            // $order->cell_number = $request->cell;
            $order->payer_id = $_GET['PayerID'];
            if ($order->save()) {
                $carts = Cart::where("customerId", Auth::user()->id)->get();

                foreach ($carts as $item) {
                    $products = Product::find($item->productId);
                    $orderItem = new OrderItem;
                    $orderItem->productId = $item->productId;
                    $orderItem->quantites = $item->quantites;
                    $orderItem->price = $products->price;
                    $orderItem->orderId = $order->id;
                    $orderItem->save();
                    $item->delete();
                }
            }


             return redirect()-> route('shopping.cart')->with('success', 'payment is success');
        }else{
            return redirect()->route('shopping.cart')->with('error', $request['message']?? "something Went wrong man..");
    
        }
    
        
    }
    
    public function paymentCancel(){
        $request='';
        return redirect()->route('shopping.cart')->with('error', $request['message'] ?? "you order has been canceled.. please.");
    }
    
    



}



//paypal install command :- composer require srmklive/paypal
//                       :- php artisan vendor:publish --provider    "Srmklive\Paypal\Providers\PayPalServiceProvider"

// paypal test email :- sb-loq47r27399795@personal.example.com
// paypal test pass :- iS$.4,0O







/**
 * response[amount];
 * 
 * 
 * 
 */