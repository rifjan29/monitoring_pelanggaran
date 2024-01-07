<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataWaliSantri extends Model
{
    use HasFactory;
    protected $table = 'wali_santri';
    protected $fillable = [
        'nama',
        'email',
        'no_telp',
        'alamat',
        'jenis_kelamin',
    ];
}
