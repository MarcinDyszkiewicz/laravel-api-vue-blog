<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    const TYPE_LIKE = 10;
    const TYPE_DISLIKE = 20;

    protected $fillable = ['user_id', 'comment_id', 'type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
