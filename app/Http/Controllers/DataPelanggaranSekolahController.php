<?php

namespace App\Http\Controllers;

use App\Models\DataSantri;
use App\Models\PelanggaranSekolah;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DataPelanggaranSekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $param['title'] = 'List Pelanggaran Sekolah';
        $param['data'] = PelanggaranSekolah::with('santri')->latest()->paginate(10);
        $param['siswa'] = DataSantri::with('wali_santri')->where('users_id',$user_id)->latest()->get();
        return view('pelanggaran-sekolah.index',$param);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = Validator::make($request->all(),[
            'nama_lengkap' => 'required|not_in:0',
            'jenis_pelanggaran' => 'required|not_in:0',
            'status_pelanggaran' => 'required|not_in:0',
            'tanggal_pelanggaran' => 'required',
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('pelanggaran-sekolah.index');
        }

        try {
            $santri = DataSantri::find($request->get('nama_lengkap'));
            $pelanggaran = new PelanggaranSekolah;
            $pelanggaran->santri_id = $request->get('nama_lengkap');
            $pelanggaran->jenis_pelanggaran = $request->get('jenis_pelanggaran');
            $pelanggaran->status_pelanggaran = $request->get('status_pelanggaran');
            $pelanggaran->keterangan_pelanggaran = $request->get('keterangan_pelanggaran');
            if ($request->hasFile('foto_bukti_pelanggaran')) {
                $file = $request->file('foto_bukti_pelanggaran');
                $filename = str_replace(' ','-',$santri->nama_lengkap).'.'.$file->extension();
                $file->storeAs('public/pelanggaran-sekolah/'.$filename);
                $pelanggaran->foto_bukti_pelanggaran = $filename;
            }
            $pelanggaran->tanggal_pelanggaran = Carbon::parse($request->get('tanggal_pelanggaran'));
            $pelanggaran->user_id = Auth::user()->id;
            $pelanggaran->save();

            alert()->success('Sukses','Berhasil menambahkan data.');
            return redirect()->route('pelanggaran-sekolah.index');
        } catch (Exception $e) {
            alert()->error('Error', 'Terjadi Kesalahan');
            return redirect()->back();
        }
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
