<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'company',
        'category',
        'bus',
        'line',
        'driver',
        'employee',
        'hour',
        'action',
        'notice_time',
        'change_bus',
        'solution_time',
        'description',
    ];
}
