<?php

namespace App\Models;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Surattugas extends Model
{
    use HasFactory;
    protected $fillable = [
        'nosurattugas',
        'statussurattugas',
        'note',
        'outlets_id'
    ];
    protected $table = 'surattugas';

    //relasi to table detail surat tugas
    public function detailsurattugas()
    {
        return $this->hasMany(Detailsurattugas::class, 'surattugas_id', 'id');
    }


    function driver() {
        return $this->belongsTo(User::class, 'driver_id', 'id');
    }


    function vehicle() {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'id');
    }


    function outlet() {
        return $this->belongsTo(Outlet::class, 'outlets_id', 'id');
    }




}
