<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory;
    protected $fillable = [
        'users_id',
        'path',
        'url',
        'method',
        'description',
        'ip_address',
        'user_agent'
    ];
    protected $table = 'log_activities';

    //return relation to users
    function user(){
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}
