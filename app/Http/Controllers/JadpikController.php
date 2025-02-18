<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadpik;
use App\Models\Hari;
use Illuminate\Validation\Rule;

class JadpikController extends Controller
{
    /**
     * Menampilkan daftar jadwal piket beserta daftar hari.
     */
    public function index()
{
    $haris = Hari::all();
    $jadpiks = [];

    foreach ($haris as $hari) {
        // Menggunakan pagination biasa di sini
        $jadpiks[$hari->id] = Jadpik::where('hari_id', $hari->id)->paginate(5);
    }

    return view('jadpik.index', compact('haris', 'jadpiks'));
}


    /**
     * Menyimpan data jadwal piket baru dengan validasi.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_siswa' => [
                'required',
                'max:255',
                'regex:/^[A-Za-z\s.]+$/',
                Rule::unique('jadpiks')->where(function ($query) use ($request) {
                    return $query->where('hari_id', $request->hari_id);
                })
            ],
            'hari_id' => 'required|exists:haris,id',
        ], [
            'nama_siswa.required' => 'Nama siswa harus diisi.',
            'nama_siswa.max' => 'Nama siswa maksimal 255 karakter.',
            'nama_siswa.regex' => 'Nama siswa hanya boleh mengandung huruf, spasi, dan titik.',
            'nama_siswa.unique' => 'Nama siswa ini sudah terdaftar untuk hari yang sama.',
            'hari_id.required' => 'Hari harus dipilih.',
            'hari_id.exists' => 'Hari yang dipilih tidak valid.',
        ]);

        Jadpik::create($request->all());

        return redirect()->back()->with('success', 'Jadwal piket berhasil ditambahkan.');
    }

    /**
     * Memperbarui data jadwal piket dengan validasi.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_siswa' => [
                'required',
                'max:255',
                'regex:/^[A-Za-z\s.]+$/',
                Rule::unique('jadpiks')->where(function ($query) use ($request, $id) {
                    return $query->where('hari_id', $request->hari_id)->where('id', '!=', $id);
                })
            ],
            'hari_id' => 'required|exists:haris,id',
        ], [
            'nama_siswa.required' => 'Nama siswa harus diisi.',
            'nama_siswa.max' => 'Nama siswa maksimal 255 karakter.',
            'nama_siswa.regex' => 'Nama siswa hanya boleh mengandung huruf, spasi, dan titik.',
            'nama_siswa.unique' => 'Nama siswa ini sudah terdaftar untuk hari yang sama.',
            'hari_id.required' => 'Hari harus dipilih.',
            'hari_id.exists' => 'Hari yang dipilih tidak valid.',
        ]);

        $jadpik = Jadpik::findOrFail($id);
        $jadpik->update($request->all());

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
