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
        $id_user = Auth::id(); // Mengambil id user yang sedang login
        $currentTime = now();

        $today = $currentTime->toDateTimeString();
        $deadline = Carbon::now()->toDateString(); // Contoh: "2025-02-13"
        $customDate = Carbon::parse($deadline)->setTime(23, 59, 0);    

        // Cek apakah jurnal hari ini sudah ada untuk user yang sedang login
        $jurnalHariIni = Jurnal::where('id_user', $id_user)
                               ->whereDate('created_at', $today)
                               ->first();

        // Jika belum ada jurnal dan waktu sudah lebih dari jam 00:00 (midnight), buat otomatis
        if (is_null($jurnalHariIni) && $today >= $customDate) {  
            $jurnalHariIni = Jurnal::create([
                'id_user' => $id_user, // Pastikan jurnal hanya milik user yang sedang login
                'judul' => 'Kosong',
                'gambar' => '', // Pastikan ada gambar default di storage
                'deskripsi' => 'Tidak ada jurnal yang diisi hari ini.',
            ]);
        }

        // Menampilkan daftar jurnal yang terkait dengan user yang sedang login
        $jurnals = Jurnal::where('id_user', $id_user)->latest()->paginate(10);
        return view('jurnals.index', compact('jurnals'));
    }

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
        $id_user = Auth::id(); // Mengambil id user yang sedang login

        $currentTime = now();
        $today = $currentTime->toDateString();

        // Cek apakah sudah ada jurnal hari ini untuk user yang sedang login
        $userLastJurnal = Jurnal::where('id_user', $id_user)
                                 ->whereDate('created_at', $today)
                                 ->first();
        if ($userLastJurnal) {
            return redirect()->route('jurnals.index')->with('error', 'Anda hanya bisa mengisi jurnal sekali dalam 24 jam.');
        }

        // Cek apakah waktu saat ini lebih dari jam 16:00
        if ($currentTime->hour < 9) {
            return redirect()->route('jurnals.index')->with('error', 'Jurnal hanya bisa diisi setelah jam 16:00.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'required|string',
        ], [
            'judul.required' => 'Judul wajib diisi.',
            'judul.max' => 'Judul tidak boleh lebih dari 255 karakter.',
            'gambar.required' => 'Gambar wajib diunggah.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'gambar.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
        ]);

        $gambarPath = $request->file('gambar')->store('jurnals', 'public');

        Jurnal::create([
            'id_user' => $id_user, // Pastikan jurnal milik user yang sedang login
            'judul' => $request->judul,
            'gambar' => $gambarPath,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('jurnals.index')->with('success', 'Jurnal berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail jurnal.
     */
    public function show(Jurnal $jurnal)
    {
        // Pastikan hanya jurnal milik user yang sedang login yang dapat dilihat
        if ($jurnal->id_user != Auth::id()) {
            return redirect()->route('jurnals.index')->with('error', 'Anda tidak memiliki akses ke jurnal ini.');
        }

        return view('jurnals.show', compact('jurnal'));
    }

    /**
     * Tampilkan formulir edit jurnal.
     */
    public function edit(Jurnal $jurnal)
    {
        // Pastikan hanya jurnal milik user yang sedang login yang dapat diedit
        if ($jurnal->id_user != Auth::id()) {
            return redirect()->route('jurnals.index')->with('error', 'Anda tidak memiliki akses untuk mengedit jurnal ini.');
        }

        return view('jurnals.edit', compact('jurnal'));
    }

    /**
     * Perbarui jurnal dalam database.
     */
    public function update(Request $request, Jurnal $jurnal)
    {
        // Pastikan hanya jurnal milik user yang sedang login yang dapat diperbarui
        if ($jurnal->id_user != Auth::id()) {
            return redirect()->route('jurnals.index')->with('error', 'Anda tidak memiliki akses untuk memperbarui jurnal ini.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'required|string',
        ], [
            'judul.required' => 'Judul wajib diisi.',
            'judul.max' => 'Judul tidak boleh lebih dari 255 karakter.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'gambar.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
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
        // Pastikan hanya jurnal milik user yang sedang login yang dapat dihapus
        if ($jurnal->id_user != Auth::id()) {
            return redirect()->route('jurnals.index')->with('error', 'Anda tidak memiliki akses untuk menghapus jurnal ini.');
        }

        Storage::disk('public')->delete($jurnal->gambar);
        $jurnal->delete();

        return redirect()->route('jurnals.index')->with('success', 'Jurnal berhasil dihapus.');
    }
}
