<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSantri extends Model
{
    use HasFactory;
    protected $table = 'data_santri';
    protected $fillable = [
        'nama_lengkap',
        'foto',
        'asal',
        'jenis_kelamin',
        'alamat_lengkap',
        'wali_santri_id',
        'asrama_id',
        'users_id',
        'kategori_sekolah',
    ];

    function wali_santri() {
        return $this->belongsTo(DataWaliSantri::class,'wali_santri_id');
    }

    function asrama() {
        return $this->belongsTo(DataAsrama::class,'asrama_id');
    }
}
