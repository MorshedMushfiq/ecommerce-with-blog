<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Cart;
use App\Models\Post;

use App\Models\Team;
use App\Models\User;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Comment;
use App\Models\Product;
use App\Models\OrderItem;
use App\Mail\RegisterMail;
use Illuminate\Http\Request;
use App\Mail\OrderConfirmMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseIsRedirected;

class CakeController extends Controller
{

    //for main page.
    public function index()
    {
        $blog = Post::all();
        $user_info = DB::table("users")->join("posts", "posts.user_id", "=", "users.id")->select("users.id", "users.name", "users.image")->get();
        $all_products = Product::all();
        $all_cate = Product::where('type', "new-arrivals")->get();
        $all_cate1 = Product::where('type', "hot-sales")->get();
        $all_team = Team::all();
        return view('index', compact("all_products", 'all_cate', 'all_cate1', "all_team", "blog", "user_info"));
    }



    //for about page
    public function about()
    {
        return view('about');
    }

    //for menu page
    public function menu()
    {
        return view('menu');
    }

    //for single product in cart
    public function productDetails($id)
    {
        $single_product = Product::find($id);
        return view('cart', compact("single_product"));
    }


    //for shopping cart
    public function shoppingCart()
    {
        if(Auth::user()->id){
            $cartItems = DB::table("products")->join("carts", "carts.productId", "products.id")->select("products.title", "products.image", "products.category", "products.quantity as pQuantites", "products.price", "carts.*")->where("carts.customerId", Auth::user()->id)->get();
            return view("shopping_cart", compact("cartItems"));
        }else{
            return redirect()->route('cake.login');
        }
    }

    //add to cart method
    public function addCart(Request $request)
    {
        if (Auth::user()->id) {
            $cart = new Cart;
            $cart->productId = $request->id;
            $cart->quantites = $request->quantity;
            $cart->customerId = Auth::user()->id;
            $cart->save();
            return redirect()->route('shopping.cart')->with("success", "Product added to cart successfull!!");
        } else {
            return redirect()->route('cake.login')->with("error", "you have to login to add to cart a product");
        }
    }


    //delete cart method
    public function deleteCart($id)
    {
        $cart_item = Cart::find($id);
        $cart_item->delete();
        return redirect()->back()->with("error", "Product deleted from cart successfull!!");
    }


    //update cart method
    public function updateCart(Request $request)
    {
        if (Auth::user()->id) {
            $cart = Cart::find($request->id);
            $cart->quantites = $request->quantity;
            $cart->save();
            return redirect()->back()->with("success", "Product Quantites updated to cart successfull!!");
        } else {
            return redirect()->route('cake.login')->with("error", "you have to login to add to cart a product");
        }
    }

    //checkout proccess pending.
    public function checkout(Request $request)
    {
        if (Auth::user()->id) {
            $order = new Order;
            $order->status = "pending";
            $order->customerId = Auth::user()->id;
            $order->bill = $request->bill;
            $order->fullname = $request->name;
            $order->email = $request->email;
            $order->cell_number = $request->cell;
            $order->address = $request->address;
            if ($order->save()) {
                $carts = Cart::where("customerId", Auth::user()->id)->get();
                foreach ($carts as $item) {
                    $product = Product::find($item->productId);
                    $orderItem = new OrderItem;
                    $orderItem->productId = $item->productId;
                    $orderItem->quantites = $item->quantites;
                    $orderItem->price = $product->price;
                    $orderItem->orderId = $order->id;
                    // order item will be saved one by one
                    $orderItem->save();
                    //cart items will be deleted one by one
                    $item->delete();
                }
            }

            return redirect()->back()->with("success", "Product order successfull!!");
        } else {
            return redirect()->route('cake.login')->with("error", "you have to login to add to cart a product");
        }
    }




    //payment and check method using stripe
    //payment method stripe
    public function paymentStripe(Request $request){
        $all_products = DB::table('products')->join("carts", "carts.productId", "products.id")->select("products.title", "products.image", 'products.price', 'carts.quantites as cQuantites', "carts.customerId", 'carts.*')->where("customerId", Auth::user()->id)->get();
        $lineItems=[];
        //dd($all_products);
        //$total_price = 0;
        foreach($all_products as $product){
            $total_cost = $product->price * $product->cQuantites; 
            $total_price = $total_cost + 60; 
            $lineItems[] = [
        
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                'name' => $product->title,
                'images' => [$product->image]
                ],
                'unit_amount' => $total_price *100,
            ],
            'quantity' => 1,
        ];
        };
        $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET"));
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            "customer_creation" => 'always',
            'success_url' => route('success').'?checkout_session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cancel'),
          ]);
    
        $order = new Order;
        $order->status= "unpaid";
        $order->bill = $total_price;
        $order->fullname = $request->name;
        $order->customerId = Auth::user()->id;
        $order->cell_number = $request->cell;
        $order->address = $request->address;
        $order->session_id = $checkout_session->id;
        $order->save();
    
          return redirect($checkout_session->url);
    
    }
    

    public function success(Request $request){
        $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET"));

        try{

            $checkout_session_id = $request->get("checkout_session_id");
            $session = $stripe->checkout->sessions->retrieve($checkout_session_id);

            
            if(!$session){
                return "session e somossha ase";
            }
            $customer = $stripe->customers->retrieve($session->customer);
            $order = Order::where("session_id", $checkout_session_id)->first();
            // dd($customer);
            
            if(!$order){
                return "order e somossha ase";
            }
            if($order->status=="unpaid"){
                $order->status = "paid";
                //for mail get email like this ($customer->email).
                $order->email = $customer->email;
                $order->save();         
                    
            }else{
                return "order paid hoitase na"; 
            }
                      
            $carts = Cart::where("customerId", Auth::user()->id)->get();
            foreach($carts as $item){
                $products = Product::find($item->productId);
                $orderItem = new OrderItem;
                $orderItem->productId = $item->productId;
                $orderItem->quantites = $item->quantites;
                $orderItem->price = $products->price;
                $orderItem->orderId = $order->id;
                $orderItem->save();
                $item->delete();
            }    
            
            $msg = "Product Pay Successfull";
            return view("success", compact("msg")); 
 

        }catch(\Exception $e){
            return "kichu ekta prblm ase vai naile e te ashar kotha nai";
        } 

    }

    public function cancel(){

    }



    public function webhook(){

        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = env("STRIPE_WEBHOOK_KEY");

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
        $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );
        } catch(\UnexpectedValueException $e) {
        // Invalid payload
            return response('', 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
        // Invalid signature
        return response('', 400);
        }

        // Handle the event
        switch ($event->type) {
        case 'checkout.session.completed':
            $session = $event->data->object;
            $sessionId = $session->id;
            $order = Order::where("session_id", $sessionId)->first();
            if($order && $order->status==="unpaid"){
                $order->status = "paid";
                $order->save();
                //send mail
            }

        // ... handle other event types
        default:
            echo 'Received unknown event type ' . $event->type;
        }

        return response('');
    }



















    //for services page
    public function services()
    {
        return view('services');
    }

    //for team page
    public function team()
    {
        return view('team');
    }

    //for testimonial page
    public function testimonial()
    {
        return view('testimonial');
    }

    //for contact page
    public function contact()
    {
        return view('contact');
    }


    //for user profile page
    public function profile()
    {
        $user = User::find(Auth::user()->id);
        return view('profile', compact("user"));
    }



    //update user info
    public function updateUser(Request $request)
    {
        //image unique name generation.
        if ($request->hasFile('file')) {
            $img = $request->file('file');
            $unique_name = md5(time() . rand() . "." . $img->getClientOriginalExtension());
            $img->move(public_path("uploads/profiles"), $unique_name);
        }
        //for register in database
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->image = $unique_name;
        $user->password = HASH::make($request->password);
        $user->save();
        return redirect()->back()->with("success", "Congratulations $request->name, Your Account is Updated");
    }






    //for register page
    public function registerPage()
    {
        return view('register');
    }

    //for login page
    public function loginPage()
    {
        return view('login');
    }





    // register any user method
    public function registerUser(Request $request)
    {
        //validation 
        $this->validate($request, [
            "email" => ["unique:users"],
        ]);

        //image unique name generation.
        if ($request->hasFile('file')) {
            $img = $request->file('file');
            $unique_name = md5(time() . rand() . "." . $img->getClientOriginalExtension());
            $img->move(public_path("uploads/profiles"), $unique_name);
        }
        //for register in database
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->image = $unique_name;
        $user->password = HASH::make($request->password);
        $user->remember_token = time().rand();
        $user->save();

        //user information array for sending in registermail.
        $user_info =[
            "id" => $user->id,
            "name" => $request->name,
            "type" => $request->type,
            "email" => $request->email,
            "password" => $request->password,
            "remember_token" => $user->remember_token,
        ];


        //send mail
        Mail::to($request->email)->send(new RegisterMail($user_info));
        return redirect()->route('cake.login')->with("success", "Congratulations $request->name, Your Account is ready, please verify your account via email");
    }

    //user account verify
    public function verify($id, $token){

        $user_data = User::find($id);
        if($user_data->user_status==false && $user_data->remember_token != NULL){
            if($user_data->remember_token == $token){
                $user_data->user_status= true;
                $user_data->remember_token = NULL;
                $user_data->update();
                return redirect()->route('cake.login')->with("success", "Congratulations, Your Account is verify");
            }else{
                return redirect()->route('cake.login')->with("error", "Sorry, wrong Information.");
            }

        }else{
            return redirect()->route('cake.login')->with("success", "Congratulations, Your Account is already verify");
        }


    }



    //login any user with verification and restrictions.
    public function loginUser(Request $request)
    {
        //finding user email and password
        // $email = $request->input('email');
        // $password = $request->input('password');
        // $user = User::where("email", $email)->where("password", $password)->first();
        //if user exists
        //if($user){
        // if($user->status=="Block"){
        //     return redirect()->route('main.login')->with("error", "You are block from admin, Please contact the admin");
        // }
        //we just hold the user id and type.
        // Session::put('id', $user->id);
        // Session::put('type', $user->type);
        //if type of a man is customer
        //     if($user->type=="Customer"){
        //         return redirect()->route("cake.index");  
        //     }elseif($user->type=="Admin"){
        //     //if type of a man is admin  
        //     return redirect()->route("admin.dashboard"); 

        //     }
        // }else{
        //     return redirect()->route('cake.login')->with("error", "Sorry, Your Email/Password is incorrect");
        // }

        //login account method

        $credetials = [
            "email" => $request->email,
            "password" => $request->password
        ];

        if (Auth::attempt($credetials)) {
            $auth_type = Auth::user()->type;
            $auth_status = Auth::user()->status;
            $auth_user_status = Auth::user()->user_status;
            $auth_remember_token = Auth::user()->remember_token;

                if ($auth_type == "Admin") {
                    return redirect()->route("admin.dashboard")->with('success', "Admin Login Success!!!!");
                }elseif($auth_type == "Customer"){
                if($auth_user_status == false || $auth_remember_token != NULL){
                        Session::flush();
                        Auth::guard("web")->logout();
                        return redirect()->route('cake.login')->with('error', "Please verify your account via mail");

                    }else{
                        if($auth_status=="Block"){
                            Session::flush();
                            Auth::guard("web")->logout();
                            return redirect()->route('cake.login')->with('error', "You have been blocked from admin, Please contact the admin");
                        }elseif($auth_status=="Active"){
                            return redirect()->route("cake.index")->with('success', "Login Success!!!!");
                        }
                    }

                }

        } else {
            return redirect()->route('cake.login')->with('error', "Email or Password has been wrong");
        }
    }




    //using social media for login
    //google login
    public function googleLogin(){
        return Socialite::driver('google')->redirect();
    }
    
    //google login callback method
    public function googleCallback(){
        try{
            $user = Socialite::driver('google')->user();
            $find_user = User::where('email', $user->email)->first();
            $google_password = "12345678";
            if(!$find_user){
                $find_user = new User;
                $find_user->name = $user->name;
                $find_user->email = $user->email;
                $find_user->image = $user->avatar;
                $find_user->password = HASH::make($google_password);
                $find_user->type = "Customer";
                $find_user->status = "Active";
                $find_user->remember_token = time(). rand();
                $find_user->save();
            }

            //getting user information for sending mail
        $user_info =[
            "id" => $find_user->id,
            "name" => $find_user->name,
            "type" => $find_user->type,
            "email" => $find_user->email,
            "password" => $google_password,
            "remember_token" => $find_user->remember_token,
        ];


        //send mail

        Mail::to($user->email)->send(new RegisterMail($user_info));

            //restriction and verification
            $auth_status = $find_user->status;
             
            $auth_user_status = $find_user->user_status;
            
            $auth_remember_token = $find_user->remember_token;
            

            if($auth_user_status == false || $auth_remember_token != NULL){
                Session::flush();
                Auth::guard("web")->logout();
                return redirect()->route('cake.login')->with('error', "Please verify your account via mail");

            }else{
                if($auth_status=="Block"){
                    Session::flush();
                    Auth::guard("web")->logout();
                    return redirect()->route('cake.login')->with('error', "You have been blocked from admin, Please contact the admin");
                }elseif($auth_status=="Active"){
                    return redirect()->route("cake.index")->with('success', "Login Success!!!!");
                }
            }

        }catch(Exception $e){
            dd($e->getMessage());
        }
    } 









    //logout method
    public function logoutUser()
    {
        Session::flush();
        Auth::guard("web")->logout();
        return redirect()->route('cake.login')->with('success', "logged out successfully");
    }




    // user orders
    public function orders()
    {
        if (Auth::user()->id) {
            $orders = Order::where('customerId', Auth::user()->id)->get();

            $all_order_products = DB::table("products")->join("order_items", "order_items.productId", 'products.id')->select("products.title", "products.image", 'products.description', 'order_items.*')->get();

            return view("myorders", compact("orders", "all_order_products"));
        } else {

            return redirect()->route('cake.login')->with('error', 'pleasee login to get access to your account');
        }
    }


    //blog page for the dashboard of a user
    public function blogPage(){
        $blog = Post::all();
        return view("admin.blog", compact('blog'));
    }

    //upload blog method
    public function uploadBlog(Request $request){
        if(Auth::user()->id){
             //unique img name generation
             if($request->hasFile('post_image')){
                $img = $request->file('post_image');
                $unique_name= md5(time().rand()). "." . $img->getClientOriginalExtension();
                $img->move(public_path("uploads/posts"), $unique_name);
            }
    
            $post = new Post;
            $post->title = $request->post_title;
            $post->post_image = $unique_name;
            $post->post_description = $request->post_description;
            $post->post_comment = $request->post_comment;
            $post->post_tags = $request->post_tags;
            $post->user_id = Auth::user()->id;
            $post->save();
            return redirect()->back()->with("success", "Post Upload Succcess");
        }
    }



        //update post
        public function updatePost(Request $request){
                if(Auth::user()->id){
                //image unique name generation.
                if($request->hasFile('post_image')){
                    $img = $request->file('post_image');
                    $unique_name = md5(time().rand().".". $img->getClientOriginalExtension());
                    $img->move(public_path("uploads/posts"), $unique_name);
                }
        
                $post = Post::find($request->id);
                $post->title=$request->title;
                if(!empty($unique_name)){
                    $post->post_image=$unique_name;
                }
                $post->title = $request->post_title;
                $post->post_description = $request->post_description;
                $post->post_comment = $request->post_comment;
                $post->post_tags = $request->post_tags;
                $post->save();
                return redirect()->back()->with("success", "Post Updated Successfull!!");
                
            }

            
        }

        //delete post.
        public function deletePost($id){
            $delete_post = Post::find($id);
            $delete_post->delete();            
            return redirect()->back()->with('success', "post deleted Successfull");
        }


        //single post method
        public function singlePost($id){
           
                $single_post = Post::find($id);
                $user_infos = DB::table("users")->join("comments", "comments.user_id", "users.id")->select("users.id", "users.name", "users.image", "users.type")->get();
                $show_comments = Comment::all();
    
                return view('single_post', compact("single_post", "user_infos", "show_comments"));
         
        }

        //upload comment method
        public function postComment(Request $request){
            if(Auth::user()->id){
                $comment = new Comment;
                $comment->comment = $request->comment;
                $comment->post_id = $request->post_id;
                $comment->user_id = Auth::user()->id;
                $comment->save();
                return back();
                
            }
        }

        //delete comment.
        public function deleteComment($id){
            if(Auth::user()->id){
                $delete_comment = Comment::find($id);
                $delete_comment->delete();            
                return redirect()->back();
            }
        }



        






        //for coupon
        public function couponPage(){
            if(Auth::user()->type=="Admin"){
                $all_coupons = Coupon::all();
                return view("admin.coupon", compact('all_coupons'));
            }else{
                redirect()->redirect()->back();
            }
        }
    
    
        public function uploadCoupon(Request $request){
            if(Auth::user()->type=="Admin"){

                $coupon = new Coupon;
                $coupon->coupon_name = $request->name;
                $coupon->coupon_code = $request->code;
                $coupon->coupon_amount = $request->amount;
                $coupon->save();
                return redirect()->back()->with("success", "Coupon Upload Succcess");
            }else{
                redirect()->redirect()->back();
            }
        }
    
    
    
                //update coupon
            public function updateCoupon(Request $request){
                    if(Auth::user()->type=="Admin"){
            
                    $coupon = Coupon::find($request->id);
                    $coupon->coupon_name = $request->name;
                    $coupon->coupon_code = $request->code;
                    $coupon->coupon_amount = $request->amount;
                    $coupon->save();
                    return redirect()->back()->with("success", "Post Updated Successfull!!");
                    
                }else{
                    redirect()->redirect()->back();
                }
    
                
            }
    
            //delete coupon.
            public function deleteCoupon($id){
                $delete_coupon = Coupon::find($id);
                $delete_coupon->delete();            
                return redirect()->back()->with('success', "coupon deleted Successfull");
            }



            //apply coupon

            public function applyCoupon(Request $request){
                // $coupon_code = $request->coupon;
                // $coupon = Coupon::find($coupon_code);
                // $code = $coupon->coupon_amount;
                // $cart_price = DB::table("products")->join("carts", "carts.productId", "products.id")->select("products.price", "carts.*")->where("carts.customerId", Auth::user()->id)->get();
                return redirect()->route("shopping.cart")->with('error', "this work is under development, please try again later.");

            } 
    





}












                    //mail information array
                    // $payment_receipt = [
                    //     "orderStatus" => "Paid",
                    //     "CustomerId" => Auth::user()->id,
                    //     "Bill" => $request->bill,
                    //     "fullname" => $request->name,
                    //     "cellNumber" => $request->cell,
                    //     "email" => $request->email,
                    //     "address" => $request->address,
                    //     "cart" => $carts = Cart::where("customerId", Auth::user()->id)->get(),
                    //     "products" => DB::table('carts')->join('products',"carts.productId", '=', 'products.id')->select("products.title", "products.description", "products.price"),
                        
                        
                    // ];

                    // Mail::to($request->email)->send(new OrderConfirmMail($payment_receipt));