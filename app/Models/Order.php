<?php

namespace App\Models;

use App\Models\Outlet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $guarded = [];


    function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    function histories()
    {
        return $this->hasMany(HistoryAwb::class, 'order_id');
    }

    function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id', 'id');
    }

    function destination()
    {
        return $this->belongsTo(Destination::class, 'destinations_id', 'id');
    }

    //relation to detailmanifests
    function detailmanifests()
    {
        return $this->hasOne(DetailManifest::class, 'orders_id', 'id');
    }

    function detailshippingcourier()
    {
        return $this->hasMany(DetailShippingCourier::class, 'orders_id', 'id');
    }
}
