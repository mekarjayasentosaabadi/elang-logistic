<?php

namespace App\Models;

use App\Models\Surattugas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function surattugas(){
        return $this->hasMany(Surattugas::class, 'vehicle_id', 'id');
    }


}
