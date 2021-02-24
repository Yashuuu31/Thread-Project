<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $table = "posts";

    public function like()  
    {
        return $this->hasMany(Like::class, 'post_id', 'id');
    }

    // public function like()
    // {
    //     return $this->belongsTo(Like::class, 'id', 'post_id');
    // }
}
