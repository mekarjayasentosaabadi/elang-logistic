<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $fillable = [
        'drivers_id',
        'police_no',
        'type',
        'no_stnk'
    ];
    protected $table = 'vehicles';

    function driver(){
        return $this->belongsTo(User::class, 'drivers_id', 'id');
    }
}
