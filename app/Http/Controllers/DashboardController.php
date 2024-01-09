<?php

namespace App\Http\Controllers;

use App\Models\DataWaliSantri;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function index() {
        $param['wali_santri_count'] = DataWaliSantri::count();
        return view('dashboard',$param);
    }
}
