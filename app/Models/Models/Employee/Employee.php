<?php

namespace App\Models\Models\Employee;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes; // Add SoftDeletes trait

    protected $fillable = [
        'id',
        'name',
        'email',
        'position',
        'salary',
        'contact',
        'user_id',
        'has_login',
    ];

    protected $dates = ['deleted_at']; // Add 'deleted_at' to the dates array

    public function user()
    {
        return $this->hasOne(User::class);
    }
}

