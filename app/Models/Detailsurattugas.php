<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailsurattugas extends Model
{
    use HasFactory;
    protected $fillable = [
        'surattugas_id',
        'traveldocuments_id'
    ];
    protected $table = 'detailsurattugas';

    public function surattugas(): belongsTo{
        return $this->belongsTo(Surattugas::class, 'surattugas_id', 'id');
    }

    public function traveldocument(): belongsTo{
        return $this->belongsTo(Traveldocument::class, 'traveldocuments_id', 'id');
    }
}
