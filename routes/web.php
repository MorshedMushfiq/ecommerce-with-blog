<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CakeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\PaypalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//for admin routes
    Route::group(["middleware"=>"admin"], function(){
        //for dashboard
        Route::get('/dashboard',[AdminController::class, 'index'])->name('admin.dashboard');
        
        //for products
        Route::get('/dashboard/products',[AdminController::class, 'productPage'])->name('admin.product');
        //for upload product url
        Route::post('/dashboard/products/add_product',[AdminController::class, 'upload'])->name('product.add');
        
        //products trash page
        Route::get('/dashboard/products/trash', [AdminController::class, 'trashPage'])->name('all.trash');
        
        //trash any product
        Route::get('/dashboard/products/trash/{id}', [AdminController::class, 'trash'])->name('trash.product');
        
        //restore any product from trash page
        Route::get('/dashboard/products/trash/restore/{id}', [AdminController::class, 'restore'])->name('products.restore');
        //delete permanently a product from website and database too.
        Route::get('/dashboard/products/trash/force_delete/{id}', [AdminController::class, 'forceDelete'])->name('products.delete');
        
        //update product
        Route::post('/dashboard/product_update/{id}', [AdminController::class, 'updateProduct'])->name('update.product');
        
        //team page
        Route::get('/dashboard/team',[AdminController::class, 'teamPage'])->name('team.page');

        //add new team member
        Route::post('/dashboard/team/add_member',[AdminController::class, 'newTeamMember'])->name('team.add');

        //team member trash page
        Route::get('/dashboard/team/trash', [AdminController::class, 'teamTrashPage'])->name('team.trash.page');

        // trash any team member with this url
        Route::get('/dashboard/team/trash/{id}', [AdminController::class, 'teamTrash'])->name('trash.team');

        //restore any team member data from trash page
        Route::get('/dashboard/team/trash/restore/{id}', [AdminController::class, 'teamRestore'])->name('team.restore');

        //delete permanently a team member data from website and database too.
        Route::get('/dashboard/team/trash/force_delete/{id}', [AdminController::class, 'teamForceDelete'])->name('team.delete');

        //update any team member
        Route::post('/dashboard/team_update/{id}', [AdminController::class, 'updateTeam'])->name('update.team');

        //customers page
        Route::get('/dashboard/our_customers', [AdminController::class, 'customers'])->name('our.customers');

        //change customer status(Block, or active)
        Route::get('/dashboard/our_customers/{status}/{id}', [AdminController::class, 'changeUserStatus'])->name('change.status');

        //order status (accept, reject, completed)
        Route::get('/dashboard/orders/{status}/{id}', [AdminController::class, 'changeOrderStatus'])->name('status.orders');

        //all orders
        Route::get('/dashboard/orders', [AdminController::class, 'allOrders'])->name('our.orders');


        });


        //any user order page
        Route::get('/dashboard/my_orders', [CakeController::class, 'orders'])->name('my.orders');

        // user blog page
        Route::get('/dashboard/blog',[CakeController::class, 'blogPage'])->name('blog.page');
        //user post upload 
        Route::post('/dashboard/blog/upload',[CakeController::class, 'uploadBlog'])->name('blog.upload');
        //user post update
        Route::post('/dashboard/blog/update/{id}',[CakeController::class, 'updatePost'])->name('update.post');
        //user post delete
        Route::get('/dashboard/blog/delete/{id}',[CakeController::class, 'deletePost'])->name('delete.post');

        //delete comment
        Route::get('/delete_comment/{id}', [CakeController::class, "deleteComment"])->name('delete.comment');


        //coupon 
        //coupons are in the development proccess.....
        Route::get('/dashboard/coupon',[CakeController::class, 'couponPage'])->name('coupon.page');
        Route::post('/dashboard/coupon/upload',[CakeController::class, 'uploadCoupon'])->name('coupon.upload');
        Route::post('/dashboard/coupon/update/{id}',[CakeController::class, 'updateCoupon'])->name('update.coupon');
        Route::get('/dashboard/coupon/delete/{id}',[CakeController::class, 'deleteCoupon'])->name('delete.coupon');

        Route::post('/apply-coupon',[CakeController::class, 'applyCoupon'])->name('apply.coupon');



//user profile
Route::get('/dashboard/profile', [CakeController::class, 'profile'])->name('people.profile');

//user profile update
Route::post('/dashboard/profile/update', [CakeController::class, 'updateUser'])->name('profile.update');

//all urls
//main page
Route::get('/', [CakeController::class, 'index'])->name('cake.index');
//about page
Route::get('/about', [CakeController::class, 'about'])->name('cake.about');
//menu page
Route::get('/menu', [CakeController::class, 'menu'])->name('cake.menu');
//product details page
Route::get('/product_details/{id}', [CakeController::class, 'productDetails'])->name('cake.cart');
//add to cart method url
Route::post('/cart', [CakeController::class, 'addCart'])->name('add.cart');
//delete cart
Route::get('/delete_cart/{id}', [CakeController::class, 'deleteCart'])->name('delete.cart');

//update cart quantites
Route::post('/update_cart', [CakeController::class, 'updateCart'])->name('update.cart');
//checkout pending proccess
Route::post('/checkout', [CakeController::class, 'checkout'])->name('check.out');


//stripe checkout proccess
Route::post('/payment-stripe', [CakeController::class, "paymentStripe"])->name('payment.stripe');
Route::get('/success', [CakeController::class, "success"])->name('success');
Route::get('/cancel', [CakeController::class, "cancel"])->name('cancel');
Route::post('/webhook', [CakeController::class, "webhook"])->name('webhook');


//paypal
Route::post('/request-paypal', [PaypalController::class, 'requestPaypal'])->name('request.paypal');


Route::get('/payment-success',[PaypalController::class, 'paymentSuccess'])->name('payment.success');


Route::get('/payment-cancel',[PaypalController::class, 'paymentCancel'])->name('payment.cancel');




//shopping cart
Route::get('/shopping_cart', [CakeController::class, 'shoppingCart'])->name('shopping.cart');
//services
Route::get('/services', [CakeController::class, 'services'])->name('cake.services');
//team
Route::get('/team', [CakeController::class, 'team'])->name('cake.team');
//testimonial
Route::get('/testimonial', [CakeController::class, 'testimonial'])->name('cake.testimonial');
//contact
Route::get('/contact', [CakeController::class, 'contact'])->name('cake.contact');
//single post
Route::get('/single_post/{id}', [CakeController::class, 'singlePost'])->name('single.post');
//post a comment
Route::post('/post_comment', [CakeController::class, 'postComment'])->name('comment.post');

//authenticated user cannot go to the login page.
Route::group(['middleware'=>'redirectifauthenticated'], function(){
    Route::get('/login', [CakeController::class, 'loginPage'])->name('cake.login');
});

//register page
Route::get('/register', [CakeController::class, 'registerPage'])->name('cake.register');
//logout
Route::get('/logout', [CakeController::class, 'logoutUser'])->name('cake.logout');
//register any user
Route::post('/register_user', [CakeController::class, 'registerUser'])->name('cake.register.user');
//login any user
Route::post('/login_user', [CakeController::class, 'loginUser'])->name('cake.login.user');





//user verification url in mail
Route::get('/user_verify/{id}/{remember_token}', [CakeController::class, 'verify'])->name('verify.user');



//google login
Route::get('/google/login', [CakeController::class, "googleLogin"])->name('google.login');

//gopogle callback method url
Route::get('/auth/google/login/callback', [CakeController::class, "googleCallback"])->name('google.callback');





