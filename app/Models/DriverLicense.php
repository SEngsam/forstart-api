<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverLicense extends Model
{
    use HasFactory;
    protected $fillable = [
        'driver_id',
        'drives_license_number', 
        'drives_license_front',
        'drives_license_back',
         'expiration_date',
        ];

}
