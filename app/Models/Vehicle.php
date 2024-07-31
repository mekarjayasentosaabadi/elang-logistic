<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $fillable = [
        'police_no',
        'type',
        'no_stnk',
        'is_active'
    ];
    protected $table = 'vehicles';

    //relation to traveldocument
    public function traveldocuments(){
        return $this->hasMany(Traveldocument::class, 'vehicle_id', 'id');
    }
}
