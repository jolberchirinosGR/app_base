<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'period',
        'repeat',
        'date',
        'hour',
        'days',
        'status',
    ];

    /**
     * Get the users assigned to this task.
     */
    public function users() {
    	return $this->belongsToMany(User::class);
    }
}
