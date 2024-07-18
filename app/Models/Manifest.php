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
        'receive_date_time',
        'carier',
        'commodity',
        'flight_no',
        'no_bags',
        'flight_file',
        'status_manifest'
    ];
    protected $table = 'manifests';

    //return relation to orders
    function order(){
        return $this->belongsTo(Order::class, 'orders_id', 'id');
    }
}
