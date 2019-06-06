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

Auth::routes();

Route::group(['middleware' => 'auth'], function(){

    Route::get('/getData', [
        'uses' => 'DiscussionsController@create',
        'as' => 'discussions.create'
    ]);

    Route::post('discussions/store', [
        'uses' => 'DiscussionsController@store',
        'as' => 'discussions.store'
    ]);


});

Route::get('/getData','Controller@getData');
Route::post('/insert','Controller@insert');
Route::get('/delete/{id}','Controller@delete');
Route::get('/edit/{id}','Controller@edit')->name('edit');
Route::post('/update','Controller@update')->name('update');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/login/cas','Auth\LoginController@loginCas');

Route::middleware('cas.auth')->get('/cas/logout', function () {
    cas()->logout();
    return view('home');
});

Route::post('/request', 'Auth\RequestAccountController@register')->name('request');
Route::get('/request', 'Auth\RequestAccountController@showRegistrationForm')->name('request');




Route::resource('/users', 'UserController');
Route::post('/users/{id}/verify', 'UserController@verify')->name('users.verify');
Route::get('/users/list/unverified', 'UserController@listUnverified')->name('users.showUnverified');



Route::get('captcha-form', 'HomeController@captchForm');

Route::get('/', function () {
    return view('welcome');
});
Route::get('/advancedSearch', function () {
    return view('/partials/searchBar/advanced');
});
Route::get('/pending',function(){
  return view('/auth/pending');
})->name('pending');

//error messages
Route::get('/errors/perms/site', function() {
  return view('/errors/perms/site');
})->name('errors.perms.site');

Route::get('/errors/perms/resource', function() {
  return view('/errors/perms/resource');
})->name('errors.perms.resource');

Route::get('/errors/pending', function() {
  return view('/errors/pending');
})->name('errors.pending');

Route::get('/errors/isLocal', function() {
  return view('/errors/isLocal');
})->name('errors.isLocal');

Route::get('/errors/isCas', function() {
  return view('/errors/isCas');
})->name('errors.isCas');

Route::get('/errors/requestReceived', function() {
  return view('/errors/requestReceived');
})->name('errors.requestReceived');

Route::get('/errors/duplicateRequest', function() {
  return view('/errors/duplicateRequest');
})->name('errors.duplicateRequest');

// Deactivating Routes in Vendor Folders
Route::get('register', function () {return null;})->name('register');
Route::post('register', function () {return null;});
