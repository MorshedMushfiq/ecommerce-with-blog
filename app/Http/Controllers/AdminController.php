<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{

    //all orders method to get the full order and products info.
    public function allOrders(){
        if(Auth::user()->type=="Admin"){
            
            $order_item = DB::table('order_items')->join('products', 'order_items.productId', 'products.id')->select('products.title', 'products.image', 'order_items.*')->get();

            $all_order = DB::table('users')->join('orders', 'orders.customerId', 'users.id')->select('orders.*', 'users.name', 'users.email', 'users.user_status as userStatus')->get();
            return view('admin.allorders', compact('all_order', 'order_item'));
        }

    }


        // order status (confirm, completed etc)
        public function changeOrderStatus($status, $id){
            if(Auth::user()->type=="Admin"){
            $order = Order::find($id);
            $order->status = $status;
            $order->save();
            
            return redirect()->back()->with("success", "Order Status Updated Successfull!!");
            }
        }

    //customers information in dashboard
    public function customers(){
        if(Auth::user()->type=="Admin"){
            $customers = User::where("type", "Customer")->get();
            return view('admin.customers', compact("customers"));
        }
        return redirect()->back();
    }

    //admin can change user status
    public function changeUserStatus($status, $id){
        if(Auth::user()->type=="Admin"){
        $user = User::find($id);
        $user->status = $status;
        $user->save();
        
        return redirect()->back()->with("success", "User Status Updated Successfull!!");
        }
        return redirect()->back();
    }
    





    //admin main dashboard page
    public function index(){
        return view('admin.index');
    }


    //admin products page
    public function productPage(){
        $all_products = Product::all();
        return view('admin.product', compact('all_products'));
    }

    //admin upload product method
    public function upload(Request $request){
        //unique img name generation
        if($request->hasFile('image')){
            $img = $request->file('image');
            $unique_name= md5(time().rand()). "." . $img->getClientOriginalExtension();
            $img->move(public_path("uploads/products"), $unique_name);
        }

        $product = new Product;
        $product->title = $request->title;
        $product->image = $unique_name;
        $product->description = $request->description;
        $product->keywords = $request->keywords;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->category = $request->category;
        $product->category = $request->category;
        $product->type = $request->type;
        $product->save();
        return redirect()->back()->with("success", "your new product added successfull");

    }

    
    //products trash method
    public function trash($id){
        //if(Session::get('type')=="Admin"){
        $trash_products = Product::withTrashed()->find($id);
        $trash_products->delete();
        
        return redirect()->route("all.trash")->with("success", "Product Trashed Successfull!!");
        //}
        //return redirect()->back();
    }

    //trash page
    public function trashPage(){
        //if(Session::get('type')=="Admin"){
        $all_trash_products = Product::onlyTrashed()->get();
        return view("admin.trash", compact("all_trash_products"));
        //}
        //return redirect()->back();
    }

    //restore product data
    public function restore($id){
        //if(Session::get('type')=="Admin"){
        $trash_products = Product::withTrashed()->find($id);
        if(!is_null($trash_products)){
            $trash_products->restore();
        }
        
        return redirect()->route('admin.product')->with("success", "Product Data Restore Successfull!!");
        //}
        //return redirect()->back();
    }

    //delete permanently product data
    public function forceDelete($id){
        //if(Session::get('type')=="Admin"){
        $trash_products = Product::withTrashed()->find($id);
        
        if(!is_null($trash_products)){
            $trash_products->forceDelete();
        }
        
        return redirect()->route("all.trash")->with("success", "Product Delete permanently Successfull!!");
        //}
        //return redirect()->back();
    }

        //update product
        public function updateProduct(Request $request){
            //if(Session::get('type')=="Admin"){
            //image unique name generation.
            if($request->hasFile('image')){
                $img = $request->file('image');
                $unique_name = md5(time().rand().".". $img->getClientOriginalExtension());
                $img->move(public_path("uploads/products"), $unique_name);
            }
    
            $product = Product::find($request->id);
            $product->title=$request->title;
            if(!empty($unique_name)){
                $product->image=$unique_name;
            }
            $product->description=$request->description;
            $product->keywords=$request->keywords;
            $product->price=$request->price;
            $product->quantity=$request->quantity;
            $product->category=$request->category;
            $product->type=$request->type;
            $product->save();
            return redirect()->back()->with("success", "Product List Updated Successfull!!");
            //}
            //return redirect()->back();
        }



        // team functionality
        //team page in dashboard
        public function teamPage(){
            $my_team = Team::all();
            return view('admin.team', compact('my_team'));
        }
    
        //add new team member
        public function newTeamMember(Request $request){
            //unique img name generation
            if($request->hasFile('image')){
                $img = $request->file('image');
                $unique_name= md5(time().rand()). "." . $img->getClientOriginalExtension();
                $img->move(public_path("uploads/teams"), $unique_name);
            }
    
            $team = new Team;
            $team->name = $request->name;
            $team->image = $unique_name;
            $team->role = $request->role;
            $team->cell = $request->cell;
            $team->save();
            return redirect()->back()->with("success", "your new team member added successfull");
    
        }
    
        
        //team trash method
        public function teamTrash($id){
            //if(Session::get('type')=="Admin"){
            $trash_team = Team::withTrashed()->find($id);
            $trash_team->delete();
            
            return redirect()->route("team.trash.page")->with("success", "Product Trashed Successfull!!");
            //}
            //return redirect()->back();
        }
    
        //team trash page
        public function teamTrashPage(){
            //if(Session::get('type')=="Admin"){
            $all_trash_team = Team::onlyTrashed()->get();
            return view("admin.team_trash", compact("all_trash_team"));
            //}
            //return redirect()->back();
        }
    
        //team data restore
        public function teamRestore($id){
            //if(Session::get('type')=="Admin"){
            $trash_team = Team::withTrashed()->find($id);
            if(!is_null($trash_team)){
                $trash_team->restore();
            }
            
            return redirect()->route('team.page')->with("success", "Team Data Restore Successfull!!");
            //}
            //return redirect()->back();
        }
    
        //delete permanently team data
        public function teamForceDelete($id){
            //if(Session::get('type')=="Admin"){
            $trash_team = Team::withTrashed()->find($id);
            
            if(!is_null($trash_team)){
                $trash_team->forceDelete();
            }
            
            return redirect()->route("all.trash")->with("success", "Product Delete permanently Successfull!!");
            //}
            //return redirect()->back();
        }
    
        //update team
        public function updateTeam(Request $request){
            //if(Session::get('type')=="Admin"){
            //image unique name generation.
            if($request->hasFile('image')){
                $img = $request->file('image');
                $unique_name = md5(time().rand().".". $img->getClientOriginalExtension());
                $img->storeAs("uploads/teams", $unique_name);
            }
    
            $team = Team::find($request->id);
            if(!empty($unique_name)){
                $team->image=$unique_name;
            }
            $team->name = $request->name;
            $team->role = $request->role;
            $team->cell = $request->cell;
            $team->save();
            return redirect()->back()->with("success", "Team Updated Successfull!!");
            //}
            //return redirect()->back();
        }


            




}