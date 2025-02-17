<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen;
use App\Models\Izin;
use Illuminate\Support\Facades\Auth;

class AbsenController extends Controller
{
    public function index()
    {
        $id_user = Auth::id();
        $absens = Absen::with('user')->where('id_user', $id_user)->orderBy('tanggal', 'desc')->get();
        $izins = Izin::with('user')->where('id_user', $id_user)->get();

        return view('absens.index', compact('absens', 'izins'));
    }

    public function create()
    {
        return view('absens.create');
    }

    public function store()
    {
        $id_user = Auth::id();

        if (!$id_user) {
            return redirect()->back()->with('error', 'Anda harus login untuk melakukan absen.');
        }

        $existingIzin = Izin::where('id_user', $id_user)
                            ->whereDate('dari_tanggal', '<=', now()->toDateString())
                            ->whereDate('sampai_tanggal', '>=', now()->toDateString())
                            ->exists();

        if ($existingIzin) {
            return redirect()->back()->with('error', 'Anda tidak bisa absen karena sudah mengajukan izin.');
        }

        $lastAbsen = Absen::where('id_user', $id_user)
                          ->where('tanggal', now()->toDateString())
                          ->first();

        $currentTime = now()->setTimezone('Asia/Jakarta');

        if ($lastAbsen) {
            if ($currentTime->format('H:i:s') >= '16:00:00' && is_null($lastAbsen->absen_pulang)) {
                $lastAbsen->update(['absen_pulang' => $currentTime->format('H:i:s')]);
                return redirect()->back()->with('success', 'Absen pulang berhasil dicatat.');
            }
            return redirect()->back()->with('error', 'Anda hanya bisa melakukan absen sekali dalam sehari atau belum mencapai waktu absen pulang.');
        }

        $jamMasuk = $currentTime->format('H:i:s'); 
        $batasJamMasuk = '08:00:00';
        $keterangan = ($jamMasuk > $batasJamMasuk) ? 'alpa' : 'masuk';

        Absen::create([
            'id_user' => $id_user,
            'tanggal' => now()->toDateString(),
            'keterangan' => $keterangan,
            'absen_masuk' => $jamMasuk,
        ]);

        return redirect()->back()->with('success', 'Absen berhasil dicatat dengan status: ' . $keterangan);
    }

    public function edit($id)
    {
        $absen = Absen::with('user')->where('id_user', Auth::id())->findOrFail($id);
        return view('absens.edit', compact('absen'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|in:masuk,izin,sakit,alpa',
            'absen_masuk' => 'nullable|date_format:H:i:s',
            'absen_pulang' => 'nullable|date_format:H:i:s',
        ]);

        $absen = Absen::where('id_user', Auth::id())->findOrFail($id);
        $absen->update($request->only(['keterangan', 'absen_masuk', 'absen_pulang']));

        return redirect()->route('absens.index')->with('success', 'Absensi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $absen = Absen::where('id_user', Auth::id())->findOrFail($id);
        $absen->delete();

        return redirect()->back()->with('success', 'Absensi berhasil dihapus.');
    }
}
