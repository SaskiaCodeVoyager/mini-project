<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurnal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JurnalController extends Controller
{
    /**
     * Tampilkan daftar jurnal hanya untuk user yang sedang login.
     */
    public function index()
    {
        $today = now()->toDateString(); // Format: "2025-02-20"
        $jamSekarang = now()->hour;
        $id_user = Auth::id();

        // Cek apakah jurnal hari ini sudah ada
        $jurnalHariIni = Jurnal::whereDate('created_at', $today)->first();

        // Jika belum ada dan sudah lewat jam , buat jurnal kosong
        if (is_null($jurnalHariIni) && $jamSekarang >= 8) {
            Jurnal::create([
                'id_user' => $id_user,
                'judul' => 'Kosong',
                'gambar' => 'uploads/default.png',
                'deskripsi' => 'Hayoooo skotjam 50 kali!!.',
                'created_at' => now(), // Pastikan dibuat dengan timestamp hari ini
            ]);
        }
        $jurnals = Jurnal::with('user')->get();

        // Ambil semua jurnal terbaru
        $jurnals = Jurnal::latest()->paginate(10);
        return view('jurnals.index', compact('jurnals'));
    }

    /**
     * Simpan jurnal baru ke dalam database.
     * Hanya bisa membuat jurnal setelah jam 07:00 pagi.
     */

    /**
     * Tampilkan formulir untuk membuat jurnal baru.
     */
    public function create()
    {
        return view('jurnals.create');
    }

    /**
     * Simpan jurnal baru ke dalam database.
     */
    public function store(Request $request)
    {
        $id_user = Auth::id();
        $today = now()->toDateString();
        $jamSekarang = now()->hour;
    
        // Cek apakah sudah ada jurnal hari ini untuk user yang sedang login
        $jurnalHariIni = Jurnal::where('id_user', $id_user)
                                ->whereDate('created_at', $today)
                                ->first();
    
        // Pastikan jurnal hanya bisa dibuat setelah jam 07:00 pagi
        if ($jamSekarang < 7) {
            return redirect()->route('jurnals.index')->with('error', 'Jurnal hanya bisa dibuat setelah jam 07:00 pagi.');
        }
    
        // Jika jurnal sudah ada, berikan pesan error
        if ($jurnalHariIni) {
            return redirect()->route('jurnals.index')->with('error', 'sudah ada jurnal untuk hari ini.');
        }
    
        // Jika jurnal kosong sudah ada, update menjadi milik user
        $jurnalKosong = Jurnal::whereDate('created_at', $today)
                              ->where('judul', 'Kosong')
                              ->first();
    
        if ($jurnalKosong && is_null($jurnalKosong->id_user)) {
            $jurnalKosong->update([
                'id_user' => $id_user,
                'judul' => $request->judul,
                'gambar' => $request->file('gambar')->store('jurnals', 'public'),
                'deskripsi' => $request->deskripsi,
            ]);
        } else {
            // Validasi input
            $request->validate([
                'judul' => 'required|string|max:255',
                'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'deskripsi' => 'required|string',
            ]);
    
            // Buat jurnal baru
            Jurnal::create([
                'id_user' => $id_user,
                'judul' => $request->judul,
                'gambar' => $request->file('gambar')->store('jurnals', 'public'),
                'deskripsi' => $request->deskripsi,
            ]);
        }
    
        return redirect()->route('jurnals.index')->with('success', 'Jurnal berhasil ditambahkan.');
    }

    public function show(Jurnal $jurnal)
{
    // Pastikan hanya jurnal milik user yang sedang login yang dapat dilihat
    if ($jurnal->id_user != Auth::id()) {
        return redirect()->route('jurnals.index')->with('error', 'Anda tidak memiliki akses ke jurnal ini.');
    }

    // Ambil jurnal beserta data pengguna yang mengisinya
    $jurnal->load('user');

    return view('jurnals.show', compact('jurnal'));
}
    /**
     * Tampilkan formulir edit jurnal.
     */
    /**
 * Tampilkan formulir edit jurnal.
 */
public function edit(Jurnal $jurnal)
{
    // Cek apakah jurnal dibuat otomatis
    if ($jurnal->judul === 'Kosong' && $jurnal->deskripsi === 'Jurnal otomatis dibuat karena tidak ada yang mengisi hari ini.') {
        return redirect()->route('jurnals.index')->with('error', 'Jurnal otomatis tidak dapat diedit.');
    }

    $batasEdit = Carbon::parse($jurnal->created_at)->addDay()->setTime(10, 0, 0); // Jam 10 pagi besok

    if (now()->greaterThan($batasEdit)) {
        return redirect()->route('jurnals.index')->with('error', 'Jurnal hanya dapat diedit sebelum jam 10:00 pagi keesokan harinya.');
    }

    return view('jurnals.edit', compact('jurnal'));
}

public function update(Request $request, Jurnal $jurnal)
{
    // Cek apakah jurnal dibuat otomatis
    if ($jurnal->judul === 'Kosong' && $jurnal->deskripsi === 'Jurnal otomatis dibuat karena tidak ada yang mengisi hari ini.') {
        return redirect()->route('jurnals.index')->with('error', 'Jurnal otomatis tidak dapat diperbarui.');
    }

    $batasEdit = Carbon::parse($jurnal->created_at)->addDay()->setTime(10, 0, 0); // Jam 10 pagi besok

    if (now()->greaterThan($batasEdit)) {
        return redirect()->route('jurnals.index')->with('error', 'Jurnal hanya dapat diperbarui sebelum jam 10:00 pagi keesokan harinya.');
    }

    if ($jurnal->id_user != Auth::id()) {
        return redirect()->route('jurnals.index')->with('error', 'Anda tidak memiliki akses untuk memperbarui jurnal ini.');
    }

    // Validasi
    $request->validate([
        'judul' => 'required|string|max:255',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'deskripsi' => 'required|string',
    ]);

    if ($request->hasFile('gambar')) {
        Storage::disk('public')->delete($jurnal->gambar);
        $gambarPath = $request->file('gambar')->store('jurnals', 'public');
    } else {
        $gambarPath = $jurnal->gambar;
    }

    $jurnal->update([
        'judul' => $request->judul,
        'gambar' => $gambarPath,
        'deskripsi' => $request->deskripsi,
    ]);

    return redirect()->route('jurnals.index')->with('success', 'Jurnal berhasil diperbarui.');
}



    /**
     * Hapus jurnal dari database.
     */
    public function destroy(Jurnal $jurnal)
    {
        // if (now()->greaterThan(Carbon::parse($jurnal->created_at)->setTime(8, 0, 0))) {
        //     return redirect()->route('jurnals.index')->with('error', 'Jurnal hanya dapat dihapus sebelum jam 08:00 pada hari berikutnya.');
        // }

        Storage::disk('public')->delete($jurnal->gambar);
        $jurnal->delete();

        return redirect()->route('jurnals.index')->with('success', 'Jurnal berhasil dihapus.');
    }
    
} 