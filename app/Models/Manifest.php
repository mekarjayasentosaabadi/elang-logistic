<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manifest extends Model
{
    use HasFactory;
    protected $fillable = [
        'manifestno',
        'orders_id',
        'destination_from_id',
        'destination_to_id',
        'outlets_id',
        'receive_date_time',
        'carier',
        'commodity',
        'flight_no',
        'no_bags',
        'flight_file',
    ];
    protected $table = 'manifests';

    //return relation to destination from
    function destinationfrom(){
        return $this->belongsTo(Destination::class, 'destination_from_id', 'id');
    }

    //return relation to destination to
    function destinationto(){
        return $this->belongsTo(Destination::class, 'destination_to_id', 'id');
    }
}
