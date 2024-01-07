<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailLaporanSekolah extends Model
{
    use HasFactory;
    protected $table = 'detail_laporan_sekolah';
    protected $fillable = [
        'laporan_sekolah_id',
        'pelanggaran_sekolah_id',
    ];
}
