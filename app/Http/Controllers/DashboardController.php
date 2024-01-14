<?php

namespace App\Http\Controllers;

use App\Models\DataAsrama;
use App\Models\DataSantri;
use App\Models\DataWaliSantri;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function index() {
        $param['wali_santri_count'] = DataWaliSantri::count();
        $param['asrama_count'] = DataAsrama::count();
        $param['santri_count'] = DataSantri::count();
        return view('dashboard',$param);
    }
}
