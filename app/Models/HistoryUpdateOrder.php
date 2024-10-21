<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryUpdateOrder extends Model
{
    use HasFactory;
    protected $table = 'history_update_orders';
    protected $guarded = [];


    public function order() {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
