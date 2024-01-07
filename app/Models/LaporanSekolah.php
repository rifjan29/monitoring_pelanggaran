<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanSekolah extends Model
{
    use HasFactory;
    protected $table = 'laporan_sekolah';
    protected $fillable = [
        'kode_laporan',
        'tanggal',
        'user_id',
    ];
}
