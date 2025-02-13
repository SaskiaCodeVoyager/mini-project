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
        ], [
            'nama.required' => 'Nama divisi wajib diisi.',
            'nama.string' => 'Nama divisi harus berupa teks.',
            'nama.max' => 'Nama divisi maksimal 255 karakter.',
            'nama.unique' => 'Nama divisi sudah ada.',
            'nama.regex' => 'Nama tahap hanya boleh terdiri dari huruf dan spasi.',
        ]);
    
        Divisi::create($request->all());
    
        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil ditambahkan.');
    }
    

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
        ], [
            'nama.required' => 'Nama divisi wajib diisi.',
            'nama.string' => 'Nama divisi harus berupa teks.',
            'nama.max' => 'Nama divisi maksimal 255 karakter.'
        ]);

        $divisi = Divisi::findOrFail($id);
        $divisi->update([ 'nama' => $request->nama ]);

        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil diperbarui!');
    }

    public function destroy($id)
{
    // Find the divisi by ID
    $divisi = Divisi::findOrFail($id);

    // Check if the divisi is being used by any user (you can replace `User` with any other model)
    if ($divisi->users()->exists()) {
        // If divisi is used, don't allow deletion and show an error message
        return redirect()->route('divisi.index')->with('error', 'Divisi tidak dapat dihapus karena masih digunakan.');
    }

    // If no dependencies, proceed with the deletion
    $divisi->delete();

    // Return success message
    return redirect()->route('divisi.index')->with('success', 'Divisi berhasil dihapus!');
}

    
}
