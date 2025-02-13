<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen;
use App\Models\Izin;
use Illuminate\Support\Facades\Auth;

class AbsenController extends Controller
{
    // Menampilkan daftar absensi
    public function index()
    {
        $absens = Absen::with('user')->orderBy('tanggal', 'desc')->get();
        $izins = Izin::with('user')->get();

        return view('absens.index', compact('absens', 'izins'));
    }

    // Menampilkan form absensi
    public function create()
    {
        return view('absens.create');
    }

    // Menyimpan data absensi otomatis saat tombol "Absen Sekarang" ditekan
    public function store()
    {
        $id_user = Auth::id(); // Ambil ID user yang sedang login
        
        if (!$id_user) {
            return redirect()->back()->with('error', 'Anda harus login untuk melakukan absen.');
        }

        // Cek apakah user sudah izin untuk hari ini
        $existingIzin = Izin::where('id_user', $id_user)
                            ->whereDate('dari_tanggal', '<=', now()->toDateString())
                            ->whereDate('sampai_tanggal', '>=', now()->toDateString())
                            ->exists();

        if ($existingIzin) {
            return redirect()->back()->with('error', 'Anda tidak bisa absen karena sudah mengajukan izin.');
        }

        // Cek apakah user sudah absen dalam 24 jam terakhir
        $lastAbsen = Absen::where('id_user', $id_user)
                          ->where('tanggal', now()->toDateString())
                          ->exists();

        if ($lastAbsen) {
            return redirect()->back()->with('error', 'Anda hanya bisa melakukan absen sekali dalam sehari.');
        }

        // Pastikan menggunakan waktu Asia/Jakarta
        $jamMasuk = now()->setTimezone('Asia/Jakarta')->format('H:i:s'); 
        $batasJamMasuk = '08:00:00';

        // Tentukan keterangan (jika lebih dari jam 08:00, statusnya "alpa")
        $keterangan = ($jamMasuk > $batasJamMasuk) ? 'alpa' : 'masuk';

        // Simpan absensi baru
        Absen::create([
            'id_user' => $id_user,
            'tanggal' => now()->toDateString(),
            'keterangan' => $keterangan,
            'absen_masuk' => $jamMasuk,
        ]);

        return redirect()->back()->with('success', 'Absen berhasil dicatat dengan status: ' . $keterangan);
    }

    // Menampilkan form edit absensi
    public function edit($id)
    {
        $absen = Absen::with('user')->findOrFail($id);
        return view('absens.edit', compact('absen'));
    }

    // Memperbarui data absensi
    public function update(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|in:masuk,izin,sakit,alpa',
            'absen_masuk' => 'nullable|date_format:H:i:s',
            'absen_pulang' => 'nullable|date_format:H:i:s',
        ]);

        $absen = Absen::findOrFail($id);
        $absen->update([
            'keterangan' => $request->keterangan,
            'absen_masuk' => $request->absen_masuk,
            'absen_pulang' => $request->absen_pulang,
        ]);

        return redirect()->route('absens.index')->with('success', 'Absensi berhasil diperbarui.');
    }

    // Menghapus data absensi
    public function destroy($id)
    {
        $absen = Absen::findOrFail($id);
        $absen->delete();

        return redirect()->back()->with('success', 'Absensi berhasil dihapus.');
    }
}
