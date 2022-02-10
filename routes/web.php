<?php
use Carbon\Traits\Rounding;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/docuute',[
	'as'=>'index.getIndex',
	'uses'=>'ProductController@getIndex'
]);
Route::get('/docuute/products/{id}',[
	'as' => 'products.getProduct',
	'uses' => 'ProductController@getProduct'
]);
Route::get('/docuute/user',function(){
	return view('template.pages.user_inform');
});
Route::get('/sign-out',[
	'as' => 'signout.logout',
	'uses' => 'Auth\LoginController@logout'
]);
Route::get('/docuute/checkout',[
	'as' => 'checkout',
	'uses' => 'ProductController@showCheckout'
]);
Route::get('/docuute/about',[
	'as' => 'about',
	'uses' => 'ProductController@showAbout'
]);
Route::get('/docuute/contact',[
	'as' => 'contact',
	'uses' => 'ProductController@showContact'
]);
Route::get('/docuute/product/{id}',[
	'as' => 'product',
	'uses' => 'ProductController@getProduct'
]);

Route::post('/docuute/product/{id}/',[
	'as' => 'products.postRating',
	'uses' => 'ProductController@postRating'
]);

Route::get('/docuute/user',[
	'as' => 'user.inform',
	'uses' => 'UserController@showUserInform'
]);
Route::post('/docuute/user',[
	'as' => 'editUser',
	'uses' => 'UserController@editUser'
]);
Route::get('/docuute/user/uploadproduct',[
	'as' => 'uploadProduct',
	'uses' => 'UserController@uploadProduct'
]);

Route::post('/docuute/user/uploadproduct',[
	'as' => 'uploadProductSave',
	'uses' => 'UserController@uploadProductSave'
]);

Route::get('/sign-up',[
	'as' => 'signup.getSignUp',
	'uses' => 'Auth\RegisterController@getSignUp'
]);
Route::post('/sign-up',[
	'as' => 'signup.postSignUp',
	'uses' => 'Auth\RegisterController@postSignUp'
]);
Route::get('/sign-in',[
	'as'=> 'signin.getSignin',
	'uses' => 'Auth\LoginController@getSignin'
]);
Route::get('docuute/user/productuser',[
	'as' => 'productuser',
	'uses' => 'UserController@getProductUser'
]);
Route::post('/sign-in',[
	'as' => 'signin.postSignin',
	'uses' => 'Auth\LoginController@postSignin'
]);

Route::post('docuute/search',[
	'as' => 'search',
	'uses' => 'ProductController@getSearch'
]);
Route::post('docuute/search/price',[
	'as' => 'search-price',
	'uses' => 'ProductController@getSearchPrice'
]);

Route::get('docuute/categories/{id}',[
	'as'=>'categories.getCategories',
	'uses' => 'ProductController@getCategories'
]);

Route::get('docuute/order',[
	'as'=> 'order.getCart',
	'uses' => 'ProductController@getCart'
]);
Route::get('docuute/order/{pid}/user/{uid}',[
	'as'=> 'order.addToCart',
	'uses' => 'ProductController@addToCart'
]);
Route::post('docuute/order/',[
	'as'=> 'order.postAddToCart',
	'uses' => 'ProductController@postAddToCart'
]);

Route::get('docuute/order/remove/{id}',[
	'as'=> 'order.removeCart',
	'uses' => 'ProductController@removeCart'
]);

Route::post('docuute/product',[
	'as' => 'product.postComment',
	'uses' => 'ProductController@postComment'
]);

Route::get('docuute/order/create/',[
	'as'=> 'order.createOrder',
	'uses' => 'ProductController@createOrder'
]);

Route::get('docuute/user/history/', [
	'as'=> 'user.getHistory',
	'uses' => 'UserController@getHistory'
]);

Route::get('docuute/user/sellhistory/', [
	'as'=> 'sellhistory.getAllNotifications',
	'uses' => 'NotificationCotroller@getAllNotifications'
]);

Route::post('docuute/user/productuser/update',[
	'as' => 'products.update',
	'uses' => 'ProductController@updateProduct'
]);
Route::get('docuute/user/productuser/update/{id}',[
	'as' => 'products.show',
	'uses' => 'ProductController@showProductUser'
]);

Route::get('docuute/user/productuser/remove/{id}',[
	'as'=> 'products.remove',
	'uses' => 'ProductController@removeProduct'
]);

Route::get('docuute/user/notification/{id}',[
	'as'=> 'notification.getNotification',
	'uses' => 'NotificationCotroller@getNotification'
]);

Route::get('docuute/user/notification/comfirm/{id}',[
	'as'=> 'notification.getComfirm',
	'uses' => 'NotificationCotroller@getComfirm'
]);
Route::get('docuute/user/notification/remove/{id}',[
	'as'=> 'notification.getRemoveNote',
	'uses' => 'NotificationCotroller@getRemoveNote'
]);
Route::get('docuute/user/history/remove/{id}',[
	'as'=> 'history.getRemoveOrder',
	'uses' => 'ProductController@getRemoveOrder'
]);

Route::get('posts', 'HomeController@posts')->name('posts');

Route::post('posts', 'HomeController@postPost')->name('posts.post');

Route::get('posts/{id}', 'HomeController@show')->name('posts.show');

Route::post('docuute/postMail',[
	'as'=> 'postMail.postMail',
	'uses' => 'UserController@postMail'
]);

Route::get('register/verify/{code}', 'Auth\RegisterController@verify');

// this source for admin
Route::post('/admin/login',[
	'as'=>'admin.login.postAdminLogin',
	'uses'=>'AdminController@postAdminLogin'
]);
Route::get('/admin/logout',[
	'as'=>'admin.getAdminLogout',
	'uses'=>'AdminController@getAdminLogout'
]);
Route::get('/admin/index',[
	'as'=>'admin.index.getIndex',
	'uses'=>'AdminController@getIndex'
]);
Route::get('/admin/login',[
	'as'=>'admin.index.getLogin',
	'uses'=>'AdminController@getLogin'
]);
Route::get('/admin/lock/{id}',[
	'as'=>'admin.index.getLock',
	'uses'=>'AdminController@getLock'
]);
Route::get('/admin/user-detail/{id}',[
	'as'=>'admin.index.getDetail',
	'uses'=>'AdminController@getDetail'
]);
Route::get('/admin/product-list',[
	'as'=>'admin.index.getProductList',
	'uses'=>'AdminController@getProductList'
]);
Route::get('/admin/user-list',[
	'as'=>'admin.index.getUserList',
	'uses'=>'AdminController@getUserList'
]);