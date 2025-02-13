<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Divisi;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $divisis = Divisi::all();
        return view('auth.register', compact('divisis'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'username' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|string|in:member', // Tambahkan role ke validasi
        'asal_sekolah' => 'nullable|string',
        'jenis_kelamin' => 'nullable|string',
        'tempat_lahir' => 'nullable|string',
        'alamat' => 'nullable|string',
        'no_hp' => 'nullable|string',
        'alamat_sekolah' => 'nullable|string',
        'no_hp_sekolah' => 'nullable|string',
        'divisi_id' => 'nullable|exists:divisis,id',
        'foto_pribadi' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Simpan data user ke database
    $user = User::create([
        'username' => $validatedData['username'],
        'email' => $validatedData['email'],
        'password' => bcrypt($validatedData['password']),
        'role' => $validatedData['role'], // Ambil role dari request
        'asal_sekolah' => $validatedData['asal_sekolah'] ?? null,
        'jenis_kelamin' => $validatedData['jenis_kelamin'] ?? null,
        'tempat_lahir' => $validatedData['tempat_lahir'] ?? null,
        'alamat' => $validatedData['alamat'] ?? null,
        'no_hp' => $validatedData['no_hp'] ?? null,
        'alamat_sekolah' => $validatedData['alamat_sekolah'] ?? null,
        'no_hp_sekolah' => $validatedData['no_hp_sekolah'] ?? null,
        'divisi_id' => $validatedData['divisi_id'] ?? null,
        'foto_pribadi' => $request->hasFile('foto_pribadi') 
            ? $request->file('foto_pribadi')->store('fotoUser', 'public') 
            : null, // Simpan foto jika ada
    ]);

//     // Simpan foto jika ada
// if ($request->hasFile('foto_pribadi')) {
//     $path = $request->file('foto_pribadi')->store('fotoUser', 'public'); // Simpan di storage/app/public/fotoUser
//     $user->foto_pribadi = $path; // Set path foto
//     $user->save(); // Simpan perubahan
// }


    return redirect()->route('login')->with('success', 'Pendaftaran berhasil!');
}



}
