<?php

namespace App\Http\Controllers;

use App\Models\Tahap;
use Illuminate\Http\Request;

class TahapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Menampilkan semua data Tahap
        $tahaps = Tahap::all();
        return view('tahap.index', compact('tahaps'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Menampilkan form untuk membuat Tahap baru
        return view('tahap.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:tahaps|max:255|regex:/^[a-zA-Z\s]+$/',
            'deskripsi' => 'required|string|max:500',
        ], [
            'nama.required' => 'Nama tahap wajib diisi.',
            'nama.string' => 'Nama tahap harus berupa teks.',
            'nama.unique' => 'Nama tahap sudah ada, silakan menggunakan nama lain.',
            'nama.max' => 'Nama tahap maksimal 255 karakter.',
            'nama.regex' => 'Nama tahap hanya boleh terdiri dari huruf dan spasi.',
        ]);        
    
        Tahap::create($request->all());
        return redirect()->route('tahap.index')->with('success', 'Tahap berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tahap $tahap)
    {
        // Menampilkan detail Tahap tertentu
        return view('tahap.show', compact('tahap'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tahap $tahap)
    {
        // Menampilkan form untuk mengedit Tahap tertentu
        return view('tahap.edit', compact('tahap'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tahap $tahap)
    {
        // Validasi data yang diterima dari form
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:500',
            
        ]);

        // Memperbarui data Tahap
        $tahap->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('tahap.index')->with('success', 'Tahap berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tahap $tahap)
{
    // Check if the Tahap is associated with any Himalan
    if ($tahap->himalans()->exists()) {
        // If there are Himalan associated, prevent deletion and return an error message
        return redirect()->route('tahap.index')->with('error', 'Tahap tidak dapat dihapus karena masih digunakan dalam Himalan.');
    }

    // If no Himalan is associated, delete the Tahap
    $tahap->delete();

    // Redirect to the index page with a success message
    return redirect()->route('tahap.index')->with('success', 'Tahap berhasil dihapus!');
}

}
