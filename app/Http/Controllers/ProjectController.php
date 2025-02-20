<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Tahap;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
{
    // Mengambil user yang sedang login
    $currentUser = Auth::user();
    
    // Mengambil semua user
    $users = User::all();

    // Mengambil projects berdasarkan user yang login (melalui tabel pivot)
    $projects = Project::with(['users', 'tahap'])->get();   
    

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
        // dd($request->all());
        // dd($request->nama);

        // Validasi input dari form
        $request->validate([
            'id_user' => 'required|array', // array untuk nama pengguna
            'id_user.*' => 'exists:users,id_user', // Validasi id_user yang valid

            'nama_project' => 'required|string|max:255', // Nama project harus ada
            'deskripsi' => 'required', // Deskripsi project harus ada
            'tahap_id' => 'required|exists:tahaps,id', // Tahap harus ada di tabel tahaps
        ]);

        $users = User::whereIn('id_user', $request->id_user)->pluck('username')->toArray();
        $namaSiswa = !empty($users) ? implode(', ', $users) : 'Tidak ada user'; // Jika kosong, beri default

        $project = Project::create([
            'nama_project' => $request->nama_project,
            'deskripsi' => $request->deskripsi,
            'tahap_id' => $request->tahap_id,
            'nama' => $namaSiswa, // Pastikan `nama` tidak kosong!
        ]);


        // dd($project);

        // Menyimpan relasi antara project dan users ke pivot table
        $project->users()->sync($request->id_user);

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
    if (!$project->users->pluck('id_user')->contains(Auth::id())) {
        abort(403); // Akses ditolak jika bukan anggota
    }
    

    // Mengambil data yang diperlukan
    $tahaps = Tahap::all(); // Mengambil semua tahapan
    $users = User::all(); // Mengambil semua user
    $currentUser = Auth::user(); // User yang sedang login

    return view('projects.edit', compact('project', 'tahaps', 'users', 'currentUser'));
}


public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'id_user' => 'required|array',
            'id_user.*' => 'exists:users,id_user',
            'nama_project' => 'required|string|max:255',
            'deskripsi' => 'required',
            'tahap_id' => 'required|exists:tahaps,id',
        ]);

        $users = User::whereIn('id_user', $request->id_user)->pluck('username')->toArray();
        $namaSiswa = !empty($users) ? implode(', ', $users) : 'Tidak ada user';

        // Cari project
        $project = Project::findOrFail($id);

        // dd([
        //     'user_login' => Auth::id(),
        //     'anggota_project' => $project->users->pluck('id_user')->toArray()
        // ]);

        // if (!$project->users->pluck('id_user')->contains(Auth::id())) {
        //     abort(403); // Cek apakah user adalah anggota proyek
        // }
        
        // Update data project
        $project->update([
            'nama_project' => $request->nama_project,
            'deskripsi' => $request->deskripsi,
            'tahap_id' => $request->tahap_id,
            'nama' => $namaSiswa,
        ]);

        // Perbarui relasi di pivot table
        // Perbarui relasi user di tabel pivot tanpa looping
        $project->users()->sync($request->id_user);

        
        return redirect()->route('projects.index')->with('success', 'Project berhasil diperbarui');
    }


    
    public function destroy($id)
    {
        $project = Project::find($id);
        // Mengecek jika project bukan milik user yang sedang login
        if (!$project) {
            abort(404, 'Project tidak ditemukan');
        }

        // Menghapus project
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project berhasil dihapus');
    }
} 