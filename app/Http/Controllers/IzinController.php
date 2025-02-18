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
    // Cek apakah user adalah admin
    if (Auth::user()->role == 'admin') {
        // Admin bisa melihat semua izin dengan status approved atau pending, tanpa mempedulikan id_user
        $izins = Izin::whereIn('status', ['approved', 'pending'])->get();
    } else {
        // User biasa (member) hanya bisa melihat izin mereka sendiri yang statusnya approved atau pending
        $id_user = Auth::id();
        $izins = Izin::where('id_user', $id_user)
                     ->whereIn('status', ['approved', 'pending'])
                     ->whereDate('dari_tanggal', '<=', now()->toDateString())
                     ->get();
    }

    return view('absen.index', compact('izins'));
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
    
        // Simpan data izin (status otomatis pending)
        $izin = Izin::create([
            'id_user' => $id_user,
            'dari_tanggal' => $request->dari_tanggal,
            'sampai_tanggal' => $request->sampai_tanggal,
            'bukti' => $buktiPath,
            'deskripsi' => $request->deskripsi,
            'status' => 'pending' // Default status
        ]);
    
        return redirect()->route('absens.index')->with('success', 'Izin berhasil diajukan dan menunggu persetujuan.');
    }

    public function show($id)
    {
        $izin = Izin::findOrFail($id);
        return view('izin.show', compact('izin'));
    }

    public function update(Request $request, $id)
    {
        $izin = Izin::findOrFail($id);

        // Validasi data status izin
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        // Update status izin
        $izin->update([
            'status' => $request->status
        ]);

        return redirect()->route('absen.index')->with('success', 'Status izin berhasil diperbarui.');
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

    public function showAllAbsen()
{
    // Cek apakah user adalah admin
    if (Auth::user()->role != 'admin') {
        return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk melihat data absen.');
    }

    // Ambil semua data absen
    $absens = Absen::all(); // Bisa menambahkan filter jika diperlukan, misalnya status absen atau tanggal tertentu
    $izins = Izin::all();

    return view('admin.absen.index', compact('absens', 'izins'));
}


}
