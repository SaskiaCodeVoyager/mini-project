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
        $users = User::all();
        $projects = Project::with('users')
        ->where('id_user', Auth::id())
        ->get(); // Eager load relasi users
        $tahaps = Tahap::all();

        return view('projects.index', compact('projects', 'tahaps', 'users'));
    }

    public function create()
    {
        $tahaps = Tahap::all();
        return view('projects.create', compact('tahaps'));
    }

    public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|array',
        'nama.*' => 'exists:users,id', // Validasi user_id yang valid
        'nama_project' => 'required|string|max:255',
        'deskripsi' => 'required',
        'tahap_id' => 'required|exists:tahaps,id',
    ]);

    $project = Project::create([
        'nama_project' => $request->nama_project,
        'deskripsi' => $request->deskripsi,
        'tahap_id' => $request->tahap_id,
        'id_user' => Auth::id(),
    ]);

    $project->users()->sync($request->nama); // Menyimpan relasi ke pivot

    return redirect()->route('projects.index')->with('success', 'Project berhasil ditambahkan');
}


    public function show(Project $project)
    {
        if ($project->id_user != Auth::id()) {
            abort(403);
        }
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        if ($project->id_user != Auth::id()) {
            abort(403);
        }
        $tahaps = Tahap::all();
        return view('projects.edit', compact('project', 'tahaps'));
    }

    public function update(Request $request, Project $project)
{
    if ($project->id_user != Auth::id()) {
        abort(403);
    }

    $request->validate([
        'nama' => 'required|array',
        'nama.*' => 'exists:users,id',
        'nama_project' => 'required|string|max:255',
        'deskripsi' => 'required',
        'tahap_id' => 'required|exists:tahaps,id',
    ]);

    $project->update([
        'nama_project' => $request->nama_project,
        'deskripsi' => $request->deskripsi,
        'tahap_id' => $request->tahap_id,
    ]);

    $project->users()->sync($request->nama); // Update relasi pivot

    return redirect()->route('projects.index')->with('success', 'Project berhasil diperbarui');
}

    public function destroy(Project $project)
    {
        if ($project->id_user != Auth::id()) {
            abort(403);
        }
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project berhasil dihapus');
    }
}