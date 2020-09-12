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
//    dd(\App\Models\User::find(1)->friendOf);
    return view('home');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::resource('profile', 'App\Http\Controllers\ProfileController')->only(['show', 'edit', 'update']);
    Route::resource('article', 'App\Http\Controllers\ArticleController');
    Route::resource('author', 'App\Http\Controllers\AuthorController');
});
