<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $guarded = [];


    function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    function histories()
    {
        return $this->hasMany(HistoryAwb::class, 'order_id');
    }
}
