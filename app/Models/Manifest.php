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

    //Make a code customer automatic
    protected static function boot(){
        parent::boot();
        static::creating(function($model){
            $lastItem = self::orderBy('manifestno', 'desc')->first();
            $lastNumber = $lastItem ? $lastItem->manifestno : 0;
            $model->manifestno = $lastNumber + 1;
        });
    }

    public function detailtraveldocuments(){
        return $this->hasMany(Detailtraveldocument::class, 'manifests_id', 'id');
    }
}
