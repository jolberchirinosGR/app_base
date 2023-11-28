<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LostObject extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'register',
        'date',
        'bus',
        'line',
        'driver',
        'description',
        'delivered_by',
        'reception_by',
        'reception_date',
        'destination',
        'destination_date'
    ];
}
