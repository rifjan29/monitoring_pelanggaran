<?php

namespace App\Http\Controllers;

use App\Models\DataAsrama;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataAsramaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $param['title'] = 'Data Asrama';
        $param['data'] = DataAsrama::when($search, function ($query) use ($search) {
            $query->where('nama_asrama', 'like', '%' . $search . '%')
                ->orWhere('wali_asuh', 'like', '%' . $search . '%');
        })
        ->latest()->paginate(10);

        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return view('asrama.index',$param);
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
            'nama_asrama' => 'required|unique:asrama,nama_asrama',
            'wali_asuh' => 'required',
            'keterangan' => 'required',
        ],[
            'required' => ':attribute data harus terisi',
        ],[
            'nama_asrama' => 'Nama Asrama',
            'wali_asuh' => 'Wali Asuh',
            'keterangan' => 'Keterangan',
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('asrama.index');
        }
        try {
            $tambah = new DataAsrama;
            $tambah->nama_asrama = $request->get('nama_asrama');
            $tambah->wali_asuh = $request->get('wali_asuh');
            $tambah->keterangan = $request->get('keterangan');
            $tambah->save();

            alert()->success('Sukses','Berhasil menambahkan data.');
            return redirect()->route('asrama.index');

        } catch (Exception $th) {
            alert()->error('Terjadi Kesalahan');
            return redirect()->route('asrama.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = DataAsrama::find($id);
        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = DataAsrama::find($id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = Validator::make($request->all(),[
            'nama_asrama' => 'required',
            'wali_asuh' => 'required',
            'keterangan' => 'required',
        ],[
            'required' => ':attribute data harus terisi',
        ],[
            'nama_asrama' => 'Nama Asrama',
            'wali_asuh' => 'Wali Asuh',
            'keterangan' => 'Keterangan',
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('asrama.index');
        }
        try {
            $edit = DataAsrama::find($request->get('id'));;
            $edit->nama_asrama = $request->get('nama_asrama');
            $edit->wali_asuh = $request->get('wali_asuh');
            $edit->keterangan = $request->get('keterangan');
            $edit->update();

            alert()->success('Sukses','Berhasil mengganti data.');
            return redirect()->route('asrama.index');

        } catch (Exception $th) {
            alert()->error('Terjadi Kesalahan');
            return redirect()->route('asrama.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DataAsrama::find($id)->delete();
        alert()->success('Sukses','Berhasil dihapus.');
        return redirect()->route('asrama.index');
    }
}
