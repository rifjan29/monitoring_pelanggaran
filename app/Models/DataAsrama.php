<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataAsrama extends Model
{
    use HasFactory;
    protected $table = 'asrama';
    protected $fillable = [
        'nama_asrama',
        'wali_asuh',
        'keterangan',
    ];
}
