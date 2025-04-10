<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as BaseUser;

class User extends BaseUser
{

    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    public function userProducts()
    {
        return $this->hasMany(UserProduct::class);
    }
}
