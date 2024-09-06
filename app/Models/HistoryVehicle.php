<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryVehicle extends Model
{
    use HasFactory;
    protected $table = 'history_vehicle';
    protected $guarded = [];

    function surattugas()
    {
        return $this->belongsTo(Surattugas::class, 'noreference', 'id');
    }

    function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'id');
    }
}
