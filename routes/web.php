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
    return view('auth.login');
});
//login logout
Route::get('/login', 'App\Http\Controllers\Auth\LoginController@index')->name('login');
Route::post('/proses_login', 'App\Http\Controllers\Auth\LoginController@proses_login')->name('proses_login');
Route::get('/logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');

Route::get('/dashboard', 'App\Http\Controllers\DashboardController@index')->name('dashboard');

Route::get('/crud', 'App\Http\Controllers\CrudController@index')->name('crud');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//form permohonan
Route::get('/permohonan', 'App\Http\Controllers\CrudController@index')->name('crud');
