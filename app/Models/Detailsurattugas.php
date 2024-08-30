<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailsurattugas extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'detailsurattugas';

    public function surattugas()
    {
        return $this->belongsTo(Surattugas::class, 'surattugas_id', 'id');
    }

    function manifest()
    {
        return $this->belongsTo(Manifest::class, 'manifest_id', 'id');
    }
    
}
