<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPrice extends Model
{
    use HasFactory;
    protected $table = 'customer_prices';
    protected $guarded = [];

    function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }
    function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }
    function customer(){
        return $this->belongsTo(User::class, 'customers_id', 'id');
    }
    function masterprice(){
        return $this->belongsTo(Masterprice::class, 'masterprices_id', 'id');
    }

    //return relation to destination
    function origin(){
        return $this->belongsTo(Destination::class, 'origin_id', 'id');
    }
}
