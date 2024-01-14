<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DataUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $param['title'] = 'List User';
        $query = User::with('roles')->when($search,function($query) use ($search) {
            $query->where('name','like','%'.$search.'%')
                 ->orWhere('email','like','%'.$search.'%');
        })->latest();
        if (Auth::user()->hasRole('admin')) {
            $param['data'] = $query->paginate(10);
        }else{
            $param['data'] = $query->role(Auth::user()->roles->pluck('name')[0])->paginate(10);
        }

        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return view('users.index',$param);
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
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'roles' => 'required|not_in:0'
        ]);

        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('user.index');
        }
        try {
            $tambah = new User;
            $tambah->name = $request->get('name');
            $tambah->email = $request->get('email');
            $tambah->password = Hash::make($request->get('password'));
            $tambah->save();
            if (auth()->user()->hasRole('admin')) {
                $tambah->assignRole($request->get('roles'));
            }else{
                $tambah->assignRole($request->get('roles'));
            };

            alert()->success('Sukse','Berhasil menambahkan data.');
            return redirect()->route('user.index');

        } catch (Exception $th) {
            alert()->error('Error','Terjadi Kesalahan');
            return redirect()->route('user.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {
        $data = User::with('roles')->find($request->id);
        return $data;
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
        User::find($id)->delete();
        alert()->success('Sukses','Berhasil dihapus.');
        return redirect()->route('user.index');
    }
}
