<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surattugas extends Model
{
    use HasFactory;
    protected $fillable = [
        'nosurattugas',
        'statussurattugas',
        'note'
    ];
    protected $table = 'surattugas';

    //relasi to table detail surat tugas
    public function detailsurattugas(): hasMany{
        return $this->hasMany(Detailsurattugas::class, 'surattugas_id', 'id');
    }
}
