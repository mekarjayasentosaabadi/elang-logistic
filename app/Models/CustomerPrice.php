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
}
