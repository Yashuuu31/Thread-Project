<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashbordController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostFavController;
use App\Http\Controllers\UserPostController;
use App\Models\User;
use App\Notifications\TestNotification;
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
    Route::get('/', [DashbordController::class, 'index'])->name('dashbord.index');
    Route::resource('user_posts', UserPostController::class);

    // Comments Route -----
    Route::post('comment/store', [CommentController::class, 'StoreComment'])->name('comment.store');
    Route::post('comment/destroy', [CommentController::class, 'DestroyComment'])->name('comment.destroy');

    // Like Route -----
    Route::post('like/store', [LikeController::class, 'PostLike'])->name('like.store');

    // Favorite Post Route ---
    Route::post('favorite/store', [PostFavController::class, 'PostFav'])->name('favorite.store');

    Route::post('notification/mark_read', [NotificationController::class, 'MarkAsRead'])->name('notification.read');
}); 