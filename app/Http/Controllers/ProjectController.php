<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Tahap;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
{
    // Mengambil user yang sedang login
    $currentUser = Auth::user();
    
    // Mengambil semua user dan project yang berhubungan dengan user yang sedang login
    $users = User::all();
    $projects = Project::with('users')
        ->where('id_user', Auth::id()) // Pastikan menggunakan id_user
        ->get(); // Eager load relasi users
    $tahaps = Tahap::all();

    // Mengirim data ke view
    return view('projects.index', compact('projects', 'tahaps', 'users', 'currentUser'));
}


    public function create()
    {
        // Mengambil semua tahapan untuk form pembuatan project
        $tahaps = Tahap::all();
        return view('projects.create', compact('tahaps'));
    }

    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'nama' => 'required|array', // array untuk nama pengguna
            'nama.*' => 'exists:users,id_user', // Validasi id_user yang valid
            'nama_project' => 'required|string|max:255', // Nama project harus ada
            'deskripsi' => 'required', // Deskripsi project harus ada
            'tahap_id' => 'required|exists:tahaps,id', // Tahap harus ada di tabel tahaps
        ]);

        // Menyimpan data project ke database
        $project = Project::create([
            'nama_project' => $request->nama_project,
            'deskripsi' => $request->deskripsi,
            'tahap_id' => $request->tahap_id,
            'id_user' => Auth::id(), // Menggunakan id_user untuk pemilik project
        ]);

        // Menyimpan relasi antara project dan users ke pivot table
        $project->users()->sync($request->nama);

        return redirect()->route('projects.index')->with('success', 'Project berhasil ditambahkan');
    }

    public function show(Project $project)
    {
        // Mengecek jika project bukan milik user yang sedang login
        if ($project->id_user != Auth::id()) {
            abort(403); // Akses ditolak
        }
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
{
    // Mengecek jika project bukan milik user yang sedang login
    if ($project->id_user != Auth::id()) {
        abort(403); // Akses ditolak
    }

    // Mengambil data yang diperlukan
    $tahaps = Tahap::all(); // Mengambil semua tahapan
    $users = User::all(); // Mengambil semua user
    $currentUser = Auth::user(); // User yang sedang login

    return view('projects.edit', compact('project', 'tahaps', 'users', 'currentUser'));
}


public function update(Request $request, $id)
{
    // Validate input
    $request->validate([
        'nama_project' => 'required|string|max:255',
        'deskripsi' => 'required',
        'tahap_id' => 'required|exists:tahaps,id',
        'nama' => 'required|array',
        'nama.*' => 'exists:users,id_user',
    ]);

    // Find the project
    $project = Project::findOrFail($id);

    // Ensure the user is the owner of the project
    if ($project->id_user != Auth::id()) {
        abort(403); // Access denied
    }

    // Update project data
    $project->update([
        'nama_project' => $request->nama_project,
        'deskripsi' => $request->deskripsi,
        'tahap_id' => $request->tahap_id,
    ]);

    // Sync users
    $project->users()->sync($request->nama);

    return redirect()->route('projects.index')->with('success', 'Project berhasil diperbarui');
}

    
    public function destroy(Project $project)
    {
        // Mengecek jika project bukan milik user yang sedang login
        if ($project->id_user != Auth::id()) {
            abort(403); // Akses ditolak
        }

        // Menghapus project
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project berhasil dihapus');
    }
}
