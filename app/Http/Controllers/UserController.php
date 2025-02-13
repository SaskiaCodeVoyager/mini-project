<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create()
    {
        $divisis = Divisi::all();

        return view('users.create', compact('divisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:users',
            'email' => 'required|string|email|max:150|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,member',
            'divisi_id' => 'required|exists:divisis,id', 
            'asal_sekolah' => 'nullable|string|max:150',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tempat_lahir' => 'nullable|string|max:150',
            'alamat' => 'nullable|string|max:150',
            'no_hp' => 'nullable|string|max:150',
            'alamat_sekolah' => 'nullable|string|max:150',
            'no_hp_sekolah' => 'nullable|string|max:150',
            'foto_pribadi' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_pribadi')) {
            $fotoPath = $request->file('foto_pribadi')->store('uploads', 'public');
        }

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'divisi_id' => $request->divisi_id,
            'asal_sekolah' => $request->asal_sekolah,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'alamat_sekolah' => $request->alamat_sekolah,
            'no_hp_sekolah' => $request->no_hp_sekolah,
            'foto_pribadi' => $fotoPath,
        ]);

        return redirect()->route('users.create')->with('success', 'User berhasil didaftarkan!');
    }
}
