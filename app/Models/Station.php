<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    // The attributes that are mass assignable
    protected $fillable = [
        'station_code',
        'station_name',
        'station_phone_number',
        'address',
    ];

    // Define the table associated with the model (optional if different from pluralized model name)
    protected $table = 'stations';
}
