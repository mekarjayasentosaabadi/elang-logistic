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

    //relation to detail manifests
    function detailmanifests(){
        return $this->hasMany(Detailmanifest::class, 'manifests_id', 'id');
    }
}
