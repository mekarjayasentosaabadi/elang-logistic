<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCourier extends Model
{
    use HasFactory;
    protected $table = "shippingcouriers";
    protected $guarded = [];

    function detailshippingcourier() {
        return $this->hasMany(DetailShippingCourier::class, 'shipping_id', 'id');
    }

    function driver() {
        return $this->belongsTo(User::class, 'driver_id', 'id');
    }
}
