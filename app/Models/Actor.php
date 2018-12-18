<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    protected $fillable = ['full_name', 'poster'];

//    /**
//     * Get the route key for the model.
//     *
//     * @return string
//     */
//    public function getRouteKeyName()
//    {
//        return 'slug';
//    }

    public function movies()
    {
        return $this->belongsToMany(Movie::class);
    }

    public function rating()
    {
        return $this->morphOne(Rating::class, 'ratingable');
    }
}
