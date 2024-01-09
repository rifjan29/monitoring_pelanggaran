<?php

namespace App\Http\Controllers;

use App\Models\DataWaliSantri;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataWaliSantriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $data = DataWaliSantri::when($search, function ($query) use ($search) {
            $query->where('nama', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('no_telp', 'like', '%' . $search . '%');
        })->latest()->paginate(10);

        $param['title'] = 'List Wali Santri';
        $param['data'] = $data;
        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('wali-santri.index',$param);
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
        $validatedData = Validator::make($request->all(),
            [
                'nama_lengkap' => 'required',
                'email' => 'required|email',
                'jenis_kelamin' => 'required|not_in:0',
                'no_telp' => 'required',
                'alamat' => 'required'
            ],[
                'required' => ':attribute data harus terisi',
            ],[
                'nama_lengkap' => 'Nama Lengkap',
                'email' => 'Email',
                'no_telp' => 'No Telp',
                'alamat' => 'Alamat',
                'jenis_kelamin' => 'Jenis Kelamin',
            ],
        );
        if ($validatedData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validatedData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('wali-santri.index');
        }
        try {
            $tambah = new DataWaliSantri;
            $tambah->nama = $request->get('nama_lengkap');
            $tambah->email = $request->get('email');
            $tambah->no_telp = $request->get('no_telp');
            $tambah->alamat = $request->get('alamat');
            $tambah->jenis_kelamin = $request->get('jenis_kelamin');
            $tambah->save();

            alert()->success('Sukses','Berhasil menambahkan data.');
            return redirect()->route('wali-santri.index');

        } catch (Exception $th) {
            alert()->error('Terjadi Kesalahan');
            return redirect()->route('wali-santri.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $waliSantri = DataWaliSantri::findOrFail($request->get('id'));

        return response()->json($waliSantri);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $waliSantri = DataWaliSantri::findOrFail($request->get('id'));

        return response()->json($waliSantri);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = Validator::make($request->all(),
            [
                'nama_lengkap' => 'required',
                'email' => 'required|email',
                'jenis_kelamin' => 'required|not_in:0',
                'no_telp' => 'required',
                'alamat' => 'required'
            ],[
                'required' => ':attribute data harus terisi',
            ],[
                'nama_lengkap' => 'Nama Lengkap',
                'email' => 'Email',
                'no_telp' => 'No Telp',
                'alamat' => 'Alamat',
                'jenis_kelamin' => 'Jenis Kelamin',
            ],
        );
        if ($validatedData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validatedData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('wali-santri.index');
        }
        try {
            $update = DataWaliSantri::find($request->get('id'));
            $update->nama = $request->get('nama_lengkap');
            $update->email = $request->get('email');
            $update->no_telp = $request->get('no_telp');
            $update->alamat = $request->get('alamat');
            $update->jenis_kelamin = $request->get('jenis_kelamin');
            $update->update();

            alert()->success('Sukses','Berhasil dirubah.');
            return redirect()->route('wali-santri.index');

        } catch (Exception $th) {
            alert()->error('Terjadi Kesalahan');
            return redirect()->route('wali-santri.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DataWaliSantri::find($id)->delete();
        alert()->success('Sukses','Berhasil dihapus.');
        return redirect()->route('wali-santri.index');
    }
}
