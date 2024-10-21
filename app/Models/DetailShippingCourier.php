<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailShippingCourier extends Model
{
    use HasFactory;
    protected $table = "detailshippingcouriers";
    protected $guarded = [];

    function shippingcourier() {
        return $this->belongsTo(ShippingCourier::class, 'shipping_id', 'id');
    }

    function order() {
        return $this->belongsTo(Order::class, 'orders_id', 'id');
    }
}
