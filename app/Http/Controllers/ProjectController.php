<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Tahap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('tahap')->where('id_user', Auth::id())->get();
        $tahaps = Tahap::all();

        return view('projects.index', compact('projects', 'tahaps'));
    }

    public function create()
    {
        $tahaps = Tahap::all();
        return view('projects.create', compact('tahaps'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nama_project' => 'required|string|max:255',
            'deskripsi' => 'required',
            'tahap_id' => 'required|exists:tahaps,id',
        ]);

        $project = new Project($request->all());
        $project->id_user = Auth::id();
        $project->save();

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
            'nama' => 'required',
            'nama_project' => 'required|string|max:255',
            'deskripsi' => 'required',
            'tahap_id' => 'required|exists:tahaps,id',
        ]);

        $project->update($request->all());

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
