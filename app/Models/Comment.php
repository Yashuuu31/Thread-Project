<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    // use HasFactory;
    use SoftDeletes;
    protected $table = 'comments';

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function master()
    {
        return $this->belongsTo(Comment::class, 'master_comment');
    }

    public function Child()
    {
        return $this->hasMany(Comment::class, 'master_comment', 'id');
    }
}
