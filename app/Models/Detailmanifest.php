<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailmanifest extends Model
{
    use HasFactory;
    protected $fillable = [
        'manifests_id',
        'orders_id',
        'description'
    ];
    protected $table = 'detailmanifests';

    //return value relation to Manifests
    function manifest(){
        return $this->belongsTo(Manifest::class, 'manifests_id', 'id');
    }

    //return value relation to orders
    function order(){
        return $this->belongsTo(Order::class, 'orders_id', 'id');
    }
}
