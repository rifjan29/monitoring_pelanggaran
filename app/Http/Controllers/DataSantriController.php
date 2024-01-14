<?php

namespace App\Http\Controllers;

use App\Models\DataAsrama;
use App\Models\DataSantri;
use App\Models\DataWaliSantri;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
        $search = $request->get('search');
        $param['title'] = 'List Santri';
        $param['data'] = DataSantri::with('wali_santri','asrama')
                    ->whereHas('wali_santri',function($query) use ($search) {
                        $query->where('nama','like', '%' . $search . '%');
                    })
                    ->orWhere('nama_lengkap','like', '%' . $search . '%')
                    ->latest()->paginate(10);
        $param['asrama'] = DataAsrama::latest()->get();
        $param['wali_santri'] = DataWaliSantri::latest()->get();

        $title = 'Hapus Santri!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
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
        $validateData = Validator::make($request->all(),[
            'nama_lengkap' => 'required|unique:data_santri,nama_lengkap',
            'asal' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required|not_in:0',
            'sekolah' => 'required|not_in:0',
            'asrama' => 'required|not_in:0',
            'wali_santri' => 'required|not_in:0',
            'alamat' => 'required',
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('santri.index');
        }
        try {
            $tambah = new DataSantri;
            $tambah->nama_lengkap = $request->get('nama_lengkap');
            $tambah->tanggal_lahir = Carbon::parse($request->get('tanggal_lahir'));
            $tambah->asal = $request->get('asal');
            $tambah->jenis_kelamin = $request->get('jenis_kelamin');
            $tambah->alamat_lengkap = $request->get('alamat');
            $tambah->wali_santri_id = $request->get('wali_santri');
            $tambah->asrama_id = $request->get('asrama');
            $tambah->users_id = Auth::user()->id;
            $tambah->kategori_sekolah = $request->get('sekolah');
            if ($request->hasFile('file_input')) {
                $file = $request->file('file_input');
                $filename = str_replace(' ','-',$request->get('nama_lengkap')).'.'.$file->extension();
                $file->storeAs('public/santri/'.$filename);
                $tambah->foto = $filename;
            }
            $tambah->save();

            alert()->success('Sukses','Berhasil menambahkan data.');
            return redirect()->route('santri.index');
        } catch (Exception $e) {
            alert()->error('Error', 'Terjadi Kesalahan');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {
        $param['data'] = DataSantri::with('wali_santri','asrama')->find($request->get('id'));
        return $param['data'];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $param['data'] = DataSantri::with('wali_santri','asrama')->find($request->get('id'));
        return $param['data'];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = Validator::make($request->all(),[
            'nama_lengkap' => 'required',
            'asal' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required|not_in:0',
            'sekolah' => 'required|not_in:0',
            'asrama' => 'required|not_in:0',
            'wali_santri' => 'required|not_in:0',
            'alamat' => 'required',
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('santri.index');
        }
        try {
            $edit = DataSantri::find($request->get('id'));
            $edit->nama_lengkap = $request->get('nama_lengkap');
            $edit->tanggal_lahir = Carbon::parse($request->get('tanggal_lahir'));
            $edit->asal = $request->get('asal');
            $edit->jenis_kelamin = $request->get('jenis_kelamin');
            $edit->alamat_lengkap = $request->get('alamat');
            $edit->wali_santri_id = $request->get('wali_santri');
            $edit->asrama_id = $request->get('asrama');
            $edit->users_id = Auth::user()->id;
            $edit->kategori_sekolah = $request->get('sekolah');
            if ($request->hasFile('file_input')) {
                $path = 'public/santri/' . $edit->foto;
                Storage::delete($path);
                $file = $request->file('file_input');
                $filename = str_replace(' ','-',$request->get('nama_lengkap')).'.'.$file->extension();
                $file->storeAs('public/santri/'.$filename);
                $edit->foto = $filename;
            }
            $edit->save();

            alert()->success('Sukses','Berhasil mengganti data.');
            return redirect()->route('santri.index');
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
        $delete = DataSantri::find($id);
        if ($delete->foto) {
            $path = 'public/santri/' . $delete->foto;
            Storage::delete($path);
        }
        $delete->delete();
        alert()->success('Sukses','Berhasil dihapus.');
        return redirect()->route('santri.index');
    }

    function updateStatus(Request $request) {
        try {
            $updateStatus = DataSantri::find($request->get('id'));
            if ($request->get('status_pondok') == 'boyong') {
                $updateStatus->tanggal_lulus = Carbon::parse($request->get('tanggal_boyong'));
            }
            $updateStatus->status_pondok = $request->get('status_pondok');
            $updateStatus->update();
            alert()->success('Sukses','Berhasil mengganti status.');
            return redirect()->route('santri.index');
        } catch (Exception $th) {
            alert()->error('Error', 'Terjadi Kesalahan');
            return redirect()->back();
        }
    }
}
