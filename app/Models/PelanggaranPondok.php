<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelanggaranPondok extends Model
{
    use HasFactory;
    protected $table  = 'pelanggaran_pondok';
    protected $fillable = [
        'santri_id',
        'jenis_pelanggaran',
        'keterangan_pelanggaran',
        'tanggal_pelanggaran',
        'user_id',
    ];
}
