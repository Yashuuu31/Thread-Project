<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use SoftDeletes;
    protected $table = "posts";

    public function like()  
    {
        return $this->hasMany(Like::class, 'post_id', 'id');
    }

    public function allLikes()
    {
        return $this->like()->where('is_liked', '!=', '0');
    }

    public function isLiked()
    {
        return $this->like()->where('user_id', Auth::user()->id)->where('is_liked', '!=', '0');
    }
    
    // Post Favorite Coede...
    public function fav()  
    {
        return $this->hasMany(FavoritePost::class, 'post_id', 'id');
    }

    public function isFav()
    {
        return $this->fav()->where('user_id', Auth::user()->id)->where('is_fav', '!=', '0');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
