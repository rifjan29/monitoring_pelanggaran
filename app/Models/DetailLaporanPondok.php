<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailLaporanPondok extends Model
{
    use HasFactory;
    protected $table = 'detail_laporan_pondok';
    protected $fillable = [
        'laporan_pondok_id',
        'pelanggaran_pondok_id',
    ];
}
