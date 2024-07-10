<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasFactory;
    protected $fillable= [
        'ops_id',
        'name',
        'address',
        'phone',
        'email',
        'lat',
        'long',
        'type'
    ];
    protected $table = 'outlets';
    protected $guarded = [];
}
