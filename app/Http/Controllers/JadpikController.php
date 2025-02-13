<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadpik;
use App\Models\Hari;

class JadpikController extends Controller
{
    /**
     * Menampilkan daftar jadwal piket beserta daftar hari.
     */
    public function index()
    {
        // Mengambil semua data hari dan jadpik
        $haris = Hari::all();
        $jadpiks = Jadpik::all();

        // Pastikan view yang digunakan sesuai dengan lokasi file Blade Anda, misalnya "jadpik.index"
        return view('jadpik.index', compact('haris', 'jadpiks'));
    }

    /**
     * Menyimpan data jadwal piket baru.
     */
    public function store(Request $request)
    {
        // Validasi inputan
        $validatedData = $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'hari_id'    => 'required|exists:haris,id',
        ]);

        // Membuat data jadpik baru
        Jadpik::create($validatedData);

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Jadwal piket berhasil ditambahkan.');
    }

    /**
     * Memperbarui data jadwal piket.
     */
    public function update(Request $request, $id)
    {
        // Validasi inputan
        $validatedData = $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'hari_id'    => 'required|exists:haris,id',
        ]);

        // Mencari data jadpik berdasarkan id
        $jadpik = Jadpik::findOrFail($id);

        // Memperbarui data
        $jadpik->update($validatedData);

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Jadwal piket berhasil diperbarui.');
    }

    /**
     * Menghapus data jadwal piket.
     */
    public function destroy($id)
    {
        // Mencari data jadpik berdasarkan id
        $jadpik = Jadpik::findOrFail($id);

        // Menghapus data
        $jadpik->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Jadwal piket berhasil dihapus.');
    }
}
