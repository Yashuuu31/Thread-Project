<?php

use App\Http\Controllers\DashbordController;
use App\Http\Controllers\UserPostController;
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



Auth::routes();

Route::get('/home',function(){
    return redirect('/dashbord');
})->name('home');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashbord', [DashbordController::class, 'index'])->name('dashbord.index');
    Route::resource('user_posts', UserPostController::class);
});