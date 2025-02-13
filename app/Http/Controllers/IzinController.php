<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Izin;
use App\Models\Absen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IzinController extends Controller
{
    public function index()
    {
        $izins = Izin::with('user')->get();
        return view('izin.index', compact('izins'));
    }

    public function create()
    {
        return view('izin.create');
    }

    public function store(Request $request)
    {
        $id_user = Auth::id();
    
        if (!$id_user) {
            return redirect()->back()->with('error', 'Anda harus login untuk mengajukan izin.');
        }
    
        // Cek apakah user sudah absen hari ini
        $sudahAbsen = Absen::where('id_user', $id_user)
                            ->whereDate('tanggal', now()->toDateString())
                            ->exists();
    
        if ($sudahAbsen) {
            return redirect()->back()->with('error', 'Anda sudah absen hari ini, tidak bisa mengajukan izin.');
        }
    
        // Cek apakah sudah mengajukan izin pada hari yang sama
        $existingIzin = Izin::where('id_user', $id_user)
                            ->whereDate('dari_tanggal', '<=', now()->toDateString())
                            ->whereDate('sampai_tanggal', '>=', now()->toDateString())
                            ->exists();
    
        if ($existingIzin) {
            return redirect()->back()->with('error', 'Anda sudah mengajukan izin untuk hari ini.');
        }
    
        // Validasi data
        $request->validate([
            'dari_tanggal' => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
            'bukti' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi' => 'required|string'
        ]);
    
        // Simpan bukti izin
        $buktiPath = $request->file('bukti')->store('bukti_izin', 'public');
    
        // Simpan data izin
        $izin = Izin::create([
            'id_user' => $id_user,
            'dari_tanggal' => $request->dari_tanggal,
            'sampai_tanggal' => $request->sampai_tanggal,
            'bukti' => $buktiPath,
            'deskripsi' => $request->deskripsi
        ]);
    
        // Update absensi agar sesuai dengan izin
        Absen::where('id_user', $id_user)
            ->whereBetween('tanggal', [$request->dari_tanggal, $request->sampai_tanggal])
            ->update(['keterangan' => 'izin', 'id_izin' => $izin->id]);
    
        return redirect()->route('absens.index')->with('success', 'Izin berhasil diajukan.');
    }
    

    public function destroy($id)
    {
        $izin = Izin::findOrFail($id);

        // Hapus file bukti jika ada
        if ($izin->bukti) {
            Storage::disk('public')->delete($izin->bukti);
        }

        $izin->delete();

        return redirect()->back()->with('success', 'Izin berhasil dihapus.');
    }
}
