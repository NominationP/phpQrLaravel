<?php

use App\Events\MessagePosted;
use Illuminate\Support\Facades\Redis;
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


Route::get('/chat', function () {
    return view('chat');
})->middleware('auth');



/*up event with redis ----2*/
Route::get('/publish', function () {
    // Route logic...

    Redis::publish('test-channel', json_encode(['cache' => 'bar']));
});
Route::get('/redisSub', function () {
    return view('redisSub');
});



/*up event with echo pusher*/
Route::get('/messages',function(){
	return App\Message::with('user')->get();
})->middleware('auth');

Route::post('/messages',function(){

	// Store the new message
	$user = Auth::user();
	
	$message = $user->messages()->create([
		'message'=>request()->get('message')
	]);

	// Announce that a new message has been posted
	broadcast(new MessagePosted($message,$user))->toOthers();
	
	return ['status'=>"ok"];

})->middleware('auth');

Auth::routes();

/*admin*/


Route::get('/home', 'HomeController@index')->name('home');
Route::get('/users/logout', 'Auth\LoginController@userLogout')->name('user.logout');

Route::prefix('admin')->group(function(){
	Route::post('/login','Auth\AdminLoginController@login')->name('admin.login.submit');

	Route::get('/login','Auth\AdminLoginController@showLoginForm')->name('admin.login');

	Route::get('/','AdminController@index')->name('admin.dashboard');

	Route::get('/logout','Auth\AdminLoginController@logout')->name('admin.logout');

	// Password reset routes
	Route::post('/password/email','Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
	Route::get('/password/reset','Auth\AdminForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
	Route::post('/password/reset','Auth\AdminResetPasswordController@reset');
	Route::get('/password/reset/{token}','Auth\AdminResetPasswordController@showResetForm')->name('admin.password.reset');
});

