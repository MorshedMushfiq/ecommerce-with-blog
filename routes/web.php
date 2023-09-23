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

    Route::group(["middleware"=>"admin"], function(){
        Route::get('/dashboard',[AdminController::class, 'index'])->name('admin.dashboard');
        
        Route::get('/dashboard/products',[AdminController::class, 'productPage'])->name('admin.product');
        Route::post('/dashboard/products/add_product',[AdminController::class, 'upload'])->name('product.add');
        
        Route::get('/dashboard/products/trash', [AdminController::class, 'trashPage'])->name('all.trash');
        
        Route::get('/dashboard/products/trash/{id}', [AdminController::class, 'trash'])->name('trash.product');
        
        Route::get('/dashboard/products/trash/restore/{id}', [AdminController::class, 'restore'])->name('products.restore');
        Route::get('/dashboard/products/trash/force_delete/{id}', [AdminController::class, 'forceDelete'])->name('products.delete');
        
        Route::post('/dashboard/product_update/{id}', [AdminController::class, 'updateProduct'])->name('update.product');
        
        Route::get('/dashboard/team',[AdminController::class, 'teamPage'])->name('team.page');
        Route::post('/dashboard/team/add_member',[AdminController::class, 'newTeamMember'])->name('team.add');

        Route::get('/dashboard/team/trash', [AdminController::class, 'teamTrashPage'])->name('team.trash.page');

        Route::get('/dashboard/team/trash/{id}', [AdminController::class, 'teamTrash'])->name('trash.team');

        Route::get('/dashboard/team/trash/restore/{id}', [AdminController::class, 'teamRestore'])->name('team.restore');
        Route::get('/dashboard/team/trash/force_delete/{id}', [AdminController::class, 'teamForceDelete'])->name('team.delete');

        Route::post('/dashboard/team_update/{id}', [AdminController::class, 'updateTeam'])->name('update.team');

        });

        Route::get('/dashboard/orders', [AdminController::class, 'allOrders'])->name('our.orders');

        Route::get('/dashboard/our_customers', [AdminController::class, 'customers'])->name('our.customers');

        Route::get('/dashboard/our_customers/{status}/{id}', [AdminController::class, 'changeUserStatus'])->name('change.status');


        Route::get('/dashboard/orders/{status}/{id}', [AdminController::class, 'changeOrderStatus'])->name('status.orders');

        Route::get('/dashboard/my_orders', [CakeController::class, 'orders'])->name('my.orders');





        Route::get('/dashboard/blog',[CakeController::class, 'blogPage'])->name('blog.page');
        Route::post('/dashboard/blog/upload',[CakeController::class, 'uploadBlog'])->name('blog.upload');
        Route::post('/dashboard/blog/update/{id}',[CakeController::class, 'updatePost'])->name('update.post');
        Route::get('/dashboard/blog/delete/{id}',[CakeController::class, 'deletePost'])->name('delete.post');



        //coupon 
        Route::get('/dashboard/coupon',[CakeController::class, 'couponPage'])->name('coupon.page');
        Route::post('/dashboard/coupon/upload',[CakeController::class, 'uploadCoupon'])->name('coupon.upload');
        Route::post('/dashboard/coupon/update/{id}',[CakeController::class, 'updateCoupon'])->name('update.coupon');
        Route::get('/dashboard/coupon/delete/{id}',[CakeController::class, 'deleteCoupon'])->name('delete.coupon');





        Route::post('/apply-coupon',[CakeController::class, 'applyCoupon'])->name('apply.coupon');





    

Route::get('/dashboard/profile', [CakeController::class, 'profile'])->name('people.profile');


Route::post('/dashboard/profile/update', [CakeController::class, 'updateUser'])->name('profile.update');

Route::get('/', [CakeController::class, 'index'])->name('cake.index');
Route::get('/about', [CakeController::class, 'about'])->name('cake.about');
Route::get('/menu', [CakeController::class, 'menu'])->name('cake.menu');
Route::get('/product_details/{id}', [CakeController::class, 'productDetails'])->name('cake.cart');
Route::post('/cart', [CakeController::class, 'addCart'])->name('add.cart');
Route::get('/delete_cart/{id}', [CakeController::class, 'deleteCart'])->name('delete.cart');

Route::post('/update_cart', [CakeController::class, 'updateCart'])->name('update.cart');
Route::post('/checkout', [CakeController::class, 'checkout'])->name('check.out');


//stripe
Route::post('/payment-stripe', [CakeController::class, "paymentStripe"])->name('payment.stripe');
// Route::post('/stripe-success', [CakeController::class, "successStripe"])->name('success.stripe');
// Route::get('/stripe-cancel', [CakeController::class, "cancelStripe"])->name('cancel.stripe');

//paypal

Route::post('/request-paypal',[PaypalController::class, 'requestPaypal'])->name('request.paypal');


Route::get('/payment-success',[PaypalController::class, 'paymentSuccess'])->name('payment.success');


Route::get('/payment-cancel',[PaypalController::class, 'paymentCancel'])->name('payment.cancel');







Route::get('/shopping_cart', [CakeController::class, 'shoppingCart'])->name('shopping.cart');
Route::get('/services', [CakeController::class, 'services'])->name('cake.services');
Route::get('/team', [CakeController::class, 'team'])->name('cake.team');
Route::get('/testimonial', [CakeController::class, 'testimonial'])->name('cake.testimonial');
Route::get('/contact', [CakeController::class, 'contact'])->name('cake.contact');
Route::get('/single_post/{id}', [CakeController::class, 'singlePost'])->name('single.post');
Route::get('/single_post/{id}', [CakeController::class, 'singlePost'])->name('single.post');
Route::post('/post_comment', [CakeController::class, 'postComment'])->name('comment.post');


Route::group(['middleware'=>'redirectifauthenticated'], function(){
    Route::get('/login', [CakeController::class, 'loginPage'])->name('cake.login');
});


Route::get('/register', [CakeController::class, 'registerPage'])->name('cake.register');
Route::get('/logout', [CakeController::class, 'logoutUser'])->name('cake.logout');
Route::post('/register_user', [CakeController::class, 'registerUser'])->name('cake.register.user');
Route::post('/login_user', [CakeController::class, 'loginUser'])->name('cake.login.user');






Route::get('/user_verify/{id}/{remember_token}', [CakeController::class, 'verify'])->name('verify.user');



//google login
Route::get('/google/login', [CakeController::class, "googleLogin"])->name('google.login');

Route::get('/auth/google/login/callback', [CakeController::class, "googleCallback"])->name('google.callback');





