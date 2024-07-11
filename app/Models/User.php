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
        'pictures'
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

    function vehicles(){
        return $this->hasMany(Vehicle::class, 'drivers_id', 'id');
    }

    function outlet(){
        return $this->hasOne(Outlet::class, 'ops_id', 'id');
    }
    //Make a code customer automatic
    protected static function boot(){
        parent::boot();
        static::creating(function($model){
            if($model->role_id == '4'){
                $latestCustomer = static::where('role_id', '4')->latest()->first();
                $latestCode = $latestCustomer ? intval(substr($latestCustomer->code_customer, 2)): 0;
                $model->code_customer = 'C-'. str_pad($latestCode + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
