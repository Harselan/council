<?php

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


Route::view( 'scan', 'scan' );

Route::resource( 'threads', 'ThreadController', [ 'except' => [ 'show', 'store', 'destroy', 'update', 'create' ] ] );
Route::post( 'threads', 'ThreadController@store' )->middleware('must-be-confirmed')->name('threads.store');

Route::get( 'threads/create', 'ThreadController@create' )->middleware('must-be-confirmed')->name('threads.create');
Route::get( 'threads/search', 'SearchController@show' )->name('threads.search');

Route::get( 'threads/{channel}', 'ThreadController@index' )->name('channels.show');

Route::get( 'threads/{channel}/{thread}', 'ThreadController@show' )->name('threads.show');
Route::patch( 'threads/{channel}/{thread}', 'ThreadController@update' )->name('threads.update');
Route::delete( 'threads/{channel}/{thread}', 'ThreadController@destroy' )->name('threads.destroy');

Route::post( 'locked-threads/{thread}', 'LockedThreadController@store' )->name('locked-threads.store')->middleware('admin');
Route::delete( 'locked-threads/{thread}', 'LockedThreadController@destroy' )->name('locked-threads.destroy')->middleware('admin');

Route::post( 'pinned-threads/{thread}', 'PinnedThreadController@store' )->name('pinned-threads.store')->middleware('admin');
Route::delete( 'pinned-threads/{thread}', 'PinnedThreadController@destroy' )->name('pinned-threads.destroy')->middleware('admin');

Route::post( 'threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store' )->name( 'threads.subscribe' );
Route::delete( 'threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy' )->name( 'threads.unsubscribe' );

Route::get( 'threads/{channel}/{thread}/replies', 'ReplyController@index' )->name( 'replies.index' );
Route::post( 'threads/{channel}/{thread}/replies', 'ReplyController@store' )->name( 'replies.store' );
Route::resource( 'replies', 'ReplyController', [ 'except' => 'store' ] );

Route::post( '/replies/{reply}/favorites', 'FavoriteController@store' )->name( 'replies.favorite' );

Route::get( '/profiles/{user}', 'ProfilesController@show' )->name( 'profile' );
Route::get( '/profiles/{user}/notifications', 'UserNotificationsController@index' )->name( 'notifications.getunread' );
Route::delete( '/profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy' )->name( 'notifications.read' );

Route::post( '/replies/{reply}/best', 'BestRepliesController@store' )->name( 'best-replies.store' );

Auth::routes();

Route::get( '/register/confirm', 'Auth\RegisterConfirmationController@index' )->name( 'register.confirm' );

Route::get('/home', 'HomeController@index')->name('home');

Route::get( 'api/users', 'Api\UserController@index' )->name('api.users');
Route::post( 'api/users/{user}/avatar', 'Api\UserAvatarController@store' )->name('avatar');

Route::group(array('prefix' => 'admin', 'middleware' => 'admin', 'namespace' => 'Admin'), function()
{
	Route::get( '/', 'DashboardController@index' )->name('admin.dashboard.index');
	Route::post( '/channels', 'ChannelController@store' )->name('admin.channels.store');
	Route::get( '/channels', 'ChannelController@index' )->name('admin.channels.index');
	Route::get( '/channels/create', 'ChannelController@create')->name('admin.channels.create');
});