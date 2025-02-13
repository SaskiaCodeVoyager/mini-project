<?php

namespace App\Http\Controllers;

use App\Models\Hari;
use Illuminate\Http\Request;

class HariController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Menampilkan semua data Hari
        $haris = Hari::all();
        return view('hari.index', compact('haris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Menampilkan form untuk membuat Hari baru
        return view('hari.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang diterima dari form
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        // Menyimpan data Hari baru
        Hari::create([
            'nama' => $request->nama,
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('hari.index')->with('success', 'Hari berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Hari $hari)
    {
        // Menampilkan detail Hari tertentu
        return view('hari.show', compact('hari'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hari $hari)
    {
        // Menampilkan form untuk mengedit Hari tertentu
        return view('hari.edit', compact('hari'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hari $hari)
    {
        // Validasi data yang diterima dari form
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        // Memperbarui data Hari
        $hari->update([
            'nama' => $request->nama,
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('hari.index')->with('success', 'Hari berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hari $hari)
    {
        // Menghapus data Hari
        $hari->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('hari.index')->with('success', 'Hari berhasil dihapus!');
    }
}
