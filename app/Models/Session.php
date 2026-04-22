<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'session';

    protected $fillable = [
        'session_date', 'start_date', 'start_time', 'end_date', 'end_time', 'status', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
