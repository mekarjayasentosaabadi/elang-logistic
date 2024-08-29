<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Vehicle;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code_customer',
        'phone',
        'address',
        'role_id',
        'is_active',
        'email',
        'password',
        'pictures',
        'outlets_id',
        'is_otomatis'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    function outlet(){
        return $this->belongsTo(Outlet::class, 'outlets_id', 'id');
    }

    function orders(){
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }

    function customerprices(){
        return $this->hasMany(CustomerPrice::class, 'customers_id', 'id');
    }

    public function traveldocuments(){
        return $this->hasMany(Traveldocument::class, 'driver_id', 'id');
    }

    function shippingcourier() {
        return $this->hasMany(ShippingCourier::class, 'driver_id', 'id');
    }

    function surattugas() {
        return $this->hasMany(Surattugas::class, 'driver_id', 'id');
    }
}
