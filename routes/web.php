<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
    return view('index');
});

Route::get('/home', 'App\Http\Controllers\HomeController@index')->middleware(['auth'])->name('home');
Route::get('/planned', 'App\Http\Controllers\DRGController@planned')->middleware(['auth'])->name('planned');
Route::get('/tobeplanned', 'App\Http\Controllers\DRGController@tobeplanned')->middleware(['auth'])->name('tobeplanned');
Route::get('/cut', 'App\Http\Controllers\DRGController@cut')->middleware(['auth'])->name('cut');
Route::get('/delete', 'App\Http\Controllers\DRGController@delete')->middleware(['auth'])->name('delete');


require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
