<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryVehicle extends Model
{
    use HasFactory;
    protected $table = 'history_vehicle';
    protected $guarded = [];
}
