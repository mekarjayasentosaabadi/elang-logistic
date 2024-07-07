<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';


    function customer_prices()
    {
        return $this->hasMany(CustomerPrice::class, 'customer_id');
    }
}
