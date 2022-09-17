<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Contestant extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, HasApiTokens;

    protected $fillable = [
        'phone_number',
        'first_name',
        'last_name',
        'birthday',
        'city',
        'email',
        'password'
    ];

    public function Contest() {
        return $this->belongsToMany(Contest::class, 'contestant_giveaway', 'giveaway_id', 'contestant_id');
    }
}
