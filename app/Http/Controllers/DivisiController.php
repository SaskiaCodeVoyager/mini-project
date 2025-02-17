<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Divisi;

class DivisiController extends Controller
{
    public function index()
    {
        $divisis = Divisi::all();
        return view('divisi.index', compact('divisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:divisis|regex:/^[a-zA-Z\s]+$/',
            'deskripsi' => 'required|string|max:255', // Menambahkan validasi untuk deskripsi
        ], [
            'nama.required' => 'Nama divisi wajib diisi.',
            'nama.string' => 'Nama divisi harus berupa teks.',
            'nama.max' => 'Nama divisi maksimal 255 karakter.',
            'nama.unique' => 'Nama divisi sudah ada.',
            'nama.regex' => 'Nama divisi hanya boleh terdiri dari huruf dan spasi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
            'deskripsi.max' => 'Deskripsi maksimal 255 karakter.',
        ]);
    
        Divisi::create($request->all());
    
        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:255', // Menambahkan validasi untuk deskripsi
        ], [
            'nama.required' => 'Nama divisi wajib diisi.',
            'nama.string' => 'Nama divisi harus berupa teks.',
            'nama.max' => 'Nama divisi maksimal 255 karakter.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
            'deskripsi.max' => 'Deskripsi maksimal 255 karakter.',
        ]);

        $divisi = Divisi::findOrFail($id);
        $divisi->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Find the divisi by ID
        $divisi = Divisi::findOrFail($id);

        // Check if the divisi is being used by any user
        if ($divisi->users()->exists()) {
            return redirect()->route('divisi.index')->with('error', 'Divisi tidak dapat dihapus karena masih digunakan.');
        }

        // Proceed with the deletion
        $divisi->delete();

        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil dihapus!');
    }
}
