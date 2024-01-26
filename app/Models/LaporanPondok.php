<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPondok extends Model
{
    use HasFactory;
    protected $table = 'laporan_pondok';
    protected $fillable = [
        'kode_laporan',
        'tanggal',
        'user_id'
    ];

    function detailLaporan() {
        return $this->hasMany(DetailLaporanPondok::class,'laporan_pondok_id');
    }

}
