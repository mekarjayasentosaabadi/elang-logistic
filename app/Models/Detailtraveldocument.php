<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailtraveldocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'traveldocuments_id',
        'manifests_id'
    ];
    protected $table = 'detailtraveldocuments';

    public function traveldocument(){
        return $this->belongsTo(Traveldocument::class, 'traveldocuments_id', 'id');
    }

    public function manifest(){
        return $this->belongsTo(Manifest::class, 'manifests_id', 'id');
    }
}
