<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['user_id', 'ratingable_id', 'ratingable_type', 'rate'];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function ratingable()
    {
        return $this->morphTo();
    }
}
