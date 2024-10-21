<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traveldocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'travelno',
        'vehicle_id',
        'driver_id',
        'destinations_id',
        'outlets_id'
    ];

    protected $table = 'traveldocuments';

    public function detailtraveldocument()
    {
        return $this->hasOne(Detailtraveldocument::class, 'traveldocuments_id', 'id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id', 'id');
    }
    //return value vechicle
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'id');
    }

    //return relation to destination
    function destination()
    {
        return $this->belongsTo(Destination::class, 'destinations_id', 'id');
    }

    //return relasion to outlet
    function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlets_id', 'id');
    }

    //relasi ke detail surat tugas
    public function detailsurattugas(): hasMany
    {
        return $this->hasMany(Detailsurattugas::class, 'traveldocuments_id', 'id');
    }
}
