<?php

namespace App\Http\Controllers;

use App\Models\DataSantri;
use App\Models\PelanggaranPondok;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DataPelanggaranPondokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $user_id = Auth::user()->id;
        $param['title'] = 'List Pelanggaran Pondok';
        $param['data'] = PelanggaranPondok::with('santri')->whereHas('santri',function($query) use ($search) {
            $query->where('nama_lengkap','like', '%' . $search . '%');

        })->latest()->paginate(10);
        $param['siswa'] = DataSantri::with('wali_santri')->latest()->get();

        $title = 'Hapus Pelanggaran!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('pelanggaran-pondok.index',$param);
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
            'tanggal_pelanggaran' => 'required',
            'jumlah_kehadiran' => 'required',
            'jumlah_absen' => 'required',
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('pelanggaran-pondok.index');
        }

        try {
            $pelanggaran = new PelanggaranPondok;
            $pelanggaran->santri_id = $request->get('nama_lengkap');
            $pelanggaran->jenis_pelanggaran = $request->get('jenis_pelanggaran');
            $pelanggaran->keterangan_pelanggaran = $request->has('keterangan_pelanggaran') ? $request->get('keterangan_pelanggaran') : '-';
            $pelanggaran->tanggal_pelanggaran = Carbon::parse($request->get('tanggal_pelanggaran'));
            $pelanggaran->user_id = Auth::user()->id;
            $pelanggaran->status_kirim = 'belum-terkirim';
            $pelanggaran->jumlah_kehadiran = $request->get('jumlah_kehadiran');
            $pelanggaran->jumlah_absen = $request->get('jumlah_absen');
            $pelanggaran->keterangan_hadir = $request->has('keterangan_hadir') ? $request->get('keterangan_hadir') : '-';
            $pelanggaran->save();

            alert()->success('Sukses','Berhasil menambahkan data.');
            return redirect()->route('pelanggaran-pondok.index');
        } catch (Exception $e) {
            alert()->error('Error', 'Terjadi Kesalahan');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {

        $data = PelanggaranPondok::with('santri')->find($request->id);
        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $data = PelanggaranPondok::with('santri')->find($request->id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = Validator::make($request->all(),[
            'nama_lengkap' => 'required|not_in:0',
            'jenis_pelanggaran' => 'required|not_in:0',
            'tanggal_pelanggaran' => 'required',
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('pelanggaran-pondok.index');
        }

        try {
            $santri = DataSantri::find($request->get('nama_lengkap'));
            $pelanggaran = PelanggaranPondok::find($request->get('id'));
            $pelanggaran->santri_id = $request->get('nama_lengkap');
            $pelanggaran->jenis_pelanggaran = $request->get('jenis_pelanggaran');
            $pelanggaran->keterangan_pelanggaran = $request->get('keterangan_pelanggaran');
            $pelanggaran->tanggal_pelanggaran = Carbon::parse($request->get('tanggal_pelanggaran'));
            $pelanggaran->user_id = Auth::user()->id;
            $pelanggaran->update();

            alert()->success('Sukses','Berhasil mengganti data.');
            return redirect()->route('pelanggaran-pondok.index');
        } catch (Exception $e) {
            alert()->error('Error', 'Terjadi Kesalahan');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $pelanggaran = PelanggaranPondok::find($id);
            $pelanggaran->delete();
            alert()->success('Sukses','Berhasil dihapus.');
            return redirect()->route('pelanggaran-pondok.index');
        } catch (Exception $e) {
            alert()->error('Error', 'Terjadi Kesalahan');
            return redirect()->back();
        }
    }

    function pdf() {
        $param['data'] = PelanggaranPondok::with('santri')->latest()->get();
        return view('pelanggaran-pondok.pdf',$param);
    }
}
