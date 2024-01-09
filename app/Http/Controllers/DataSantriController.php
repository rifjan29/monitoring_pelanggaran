<?php

namespace App\Http\Controllers;

use App\Models\DataAsrama;
use App\Models\DataSantri;
use App\Models\DataWaliSantri;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\DataTables;

class DataSantriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $param;

    public function index(Request $request)
    {
        $param['title'] = 'List Santri';
        $param['data'] = DataSantri::latest()->get();
        return view('santri.index',$param);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $param['title'] = 'Tambah Santri';
        $param['wali_santri'] = DataWaliSantri::latest()->pluck('id','nama');
        $param['asrama'] = DataAsrama::latest()->pluck('id','nama_asrama');
        return view('santri.create',$param);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
