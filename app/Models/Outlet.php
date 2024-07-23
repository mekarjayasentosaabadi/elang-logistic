<?php

namespace App\Models;

// use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Outlet extends Model
{
    use HasFactory;
    protected $fillable= [
        // 'ops_id',
        'location_id',
        'name',
        'address',
        'phone',
        'email',
        'lat',
        'long',
        'is_active',
        'type'
    ];
    protected $table = 'outlets';
    protected $guarded = [];
    function operators(){
        return $this->hasMany(User::class, 'outlets_id', 'id');
    }

    function orders(){
        return $this->hasMany(Outlet::class, 'outlet_id', 'id');
    }
}
