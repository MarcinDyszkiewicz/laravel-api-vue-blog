<?php

namespace App\Models;

use App\Post;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable, SoftDeletes;


    const ROLE_USER = 1;
    const ROLE_AUTHOR = 2;
    const ROLE_EDITOR = 4;
    const ROLE_ADMIN = 100;
    const ROLE_SUPER_ADMIN = 200;

    public static $roleMap = [
        self::ROLE_USER => 'User',
        self::ROLE_AUTHOR => 'Author',
        self::ROLE_EDITOR => 'Editor',
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_SUPER_ADMIN => 'SuperAdmin'
    ];

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'token_api',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === User::ROLE_SUPER_ADMIN;
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
