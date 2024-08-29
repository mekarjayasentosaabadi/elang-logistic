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
        'status_manifest',
        'notes',
        'no_smd',
        'outlet_id',
        'destination_id'
    ];
    protected $table = 'manifests';

    //relation to detail manifests
    function detailmanifests()
    {
        return $this->hasMany(Detailmanifest::class, 'manifests_id', 'id');
    }

    function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id', 'id');
    }

    function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id', 'id');
    }

    function detailsurattugas() {
        return $this->hasOne(Detailsurattugas::class, 'surattugas_id', 'id');
    }
}
