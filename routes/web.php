<?php

use Illuminate\Support\Facades\Route;

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
    return view('home');
});

Auth::routes();

Route::namespace('App\Http\Controllers')->middleware(['auth'])->group(function () {

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'ProfileController@index')->name('index');
        Route::get('/edit', 'ProfileController@edit')->name('edit');
        Route::put('/edit', 'ProfileController@update')->name('update');
    });

    Route::resource('articles', 'ArticleController');

    Route::resource('authors', 'AuthorController')->only(['index', 'show']);
});
