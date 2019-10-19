<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Rating
 *
 * @property int $id
 * @property int $user_id
 * @property int $ratingable_id
 * @property string $ratingable_type
 * @property int $rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $ratingable
 * @property-read \App\Models\User $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rating newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rating newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rating query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rating whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rating whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rating whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rating whereRatingableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rating whereRatingableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rating whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rating whereUserId($value)
 * @mixin \Eloquent
 */
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
