<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masterprice extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'masterprices';

    function customerprices()
    {
        return $this->hasMany(Customerprice::class, 'masterprices_id', 'id');
    }
    //return relation to destination table
    function destination()
    {
        return $this->belongsTo(Destination::class, 'destinations_id', 'id');
    }
    //return relation to outlets
    function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlets_id', 'id');
    }
    //return this
    function origin()
    {
        return $this->belongsTo(Destination::class, 'origin_id', 'id');
    }
}
