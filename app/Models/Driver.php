<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class Driver extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'firstname',
        'lastname',
        'phone',
        'password',
        'email'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function vehicle() 
    {
        return $this->hasOne(Vehicle::class);
    }

    public function driverLicense() 
    {
        return $this->hasOne(DriverLicense::class,'driver_id','id');
    }

    public function address() 
    {
        return $this->hasOne(Address::class,'user_id','id');
    }
}
