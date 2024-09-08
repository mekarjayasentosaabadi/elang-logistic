<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];
    protected $table = 'destinations';
    protected $guarded = [];

    //return relation to orders
    function orders(){
        return $this->hasMany(Order::class, 'destinations_id', 'id');
    }

    //relation to outlets
    function outlets(){
        return $this->hasMany(Outlet::class, 'location_id', 'id');
    }

    //relation to traveldocument
    function traveldocument(){
        return $this->hasMany(Traveldocument::class, 'destinations_id', 'id');
    }


    //relation to surattugas
    function surattugas(){
        return $this->hasMany(Surattugas::class, 'destination_id', 'id');
    }
    //relation to origin
    function masterprices(){
        return $this->hasMany(Masterprice::class, 'origin_id', 'id');
    }

    //relation to customer price
    function customerprice(){
        return $this->hasMany(Customerprice::class, 'origin_id', 'id');
    }
}
