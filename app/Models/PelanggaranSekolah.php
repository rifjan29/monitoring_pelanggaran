<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelanggaranSekolah extends Model
{
    use HasFactory;
    protected $table  = 'pelanggaran_sekolah';
    protected $fillable = [
        'santri_id',
        'jenis_pelanggaran',
        'status_pelanggaran',
        'keterangan_pelanggaran',
        'foto_bukti_pelanggaran',
        'tanggal_pelanggaran',
        'user_id',
    ];
}
