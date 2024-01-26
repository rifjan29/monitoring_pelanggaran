<?php

namespace App\Http\Controllers;

use App\Models\DataAsrama;
use App\Models\DataSantri;
use App\Models\DataWaliSantri;
use App\Models\PelanggaranPondok;
use App\Models\PelanggaranSekolah;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    function index() {
        $param['wali_santri_count'] = DataWaliSantri::count();
        $param['asrama_count'] = DataAsrama::count();
        $param['santri_count'] = DataSantri::count();
        $param['user_count'] = User::count();
        $param['count_pelanggaran_sekolah'] = PelanggaranSekolah::count();
        $param['count_pelanggaran_pondok'] = PelanggaranPondok::count();
        $param['pelanggaran_sekolah'] = PelanggaranSekolah::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month')
                                                        ,DB::raw("(count(id)) as total_data"))->orderBy('created_at')
                                                        ->groupBy('month')
                                                        ->get();
        $param['pelanggaran_pondok'] = PelanggaranPondok::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month')
                                                        ,DB::raw("(count(id)) as total_data"))->orderBy('created_at')
                                                        ->groupBy('month')
                                                        ->get();
        // Get the current date
        $currentDate = Carbon::now();

        $startDate = $currentDate->startOfMonth();

        // Set the end date to one year from the current date
        $endDate = $currentDate->copy()->addYear()->endOfMonth();
        $param['period'] = CarbonPeriod::create($startDate, '1 month', $endDate);;
        return view('dashboard',$param);
    }
}
