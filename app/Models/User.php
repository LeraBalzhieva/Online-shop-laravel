<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as BaseUser;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 */
class User extends BaseUser
{

    /**
     * @var \Illuminate\Support\HigherOrderCollectionProxy|mixed
     */



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

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


}
