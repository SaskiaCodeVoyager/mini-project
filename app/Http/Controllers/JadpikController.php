<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadpik;
use App\Models\Hari;
use App\Models\User;
use Illuminate\Validation\Rule;

class JadpikController extends Controller
{
    /**
     * Menampilkan daftar jadwal piket beserta daftar hari.
     */
    public function index()
{
    $users = User::all();
    $haris = Hari::all();
    $jadpiks = [];

    foreach ($haris as $hari) {
        // Menggunakan pagination biasa di sini
        $jadpiks[$hari->id] = Jadpik::where('hari_id', $hari->id)->paginate(5);
    }

    return view('jadpik.index', compact('haris', 'jadpiks', 'users'));
}


    /**
     * Menyimpan data jadwal piket baru dengan validasi.
     */
    public function store(Request $request)
{
    // dd($request->all());
    $request->validate([
        'hari_id' => 'required|exists:haris,id',
        // 'nama_siswa' => 'required|string|max:255',
        'id_user' => 'required|array',
        'id_user.*' => 'exists:users,id_user',
    ]);

    $users = User::whereIn('id_user', $request->id_user)->pluck('username')->toArray();
    $namaSiswa = implode(', ', $users); // Gabungkan nama dengan koma

    // Simpan data ke jadpik
    $jadpik = Jadpik::create([
        'hari_id' => $request->hari_id,
        'nama_siswa' => $namaSiswa,
    ]);

    // dd($jadpik, $request->id_user);
    
    $jadpik->users()->sync($request->id_user);
    

    return redirect()->back()->with('success', 'Jadwal piket berhasil ditambahkan.');
}

    /**
     * Memperbarui data jadwal piket dengan validasi.
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'hari_id' => 'required|exists:haris,id',
        'id_user' => 'required|array',
        'id_user.*' => 'exists:users,id_user',
    ]);

    $jadpik = Jadpik::findOrFail($id);
    
    // Ambil nama siswa berdasarkan ID user
    $users = User::whereIn('id_user', $request->id_user)->pluck('username')->toArray();
    $namaSiswa = implode(', ', $users); // Gabungkan nama dengan koma

    // Update data jadpik
    $jadpik->update([
        'hari_id' => $request->hari_id,
        'nama_siswa' => $namaSiswa, // Pastikan nama_siswa diperbarui
    ]);

    // Update relasi pivot
    $jadpik->users()->sync($request->id_user);

    return redirect()->back()->with('success', 'Jadwal piket berhasil diperbarui.');
}


    /**
     * Menghapus data jadwal piket.
     */
    public function destroy($id)
    {
        $jadpik = Jadpik::findOrFail($id);
        $jadpik->delete();

        return redirect()->back()->with('success', 'Jadwal piket berhasil dihapus.');
    }
}
