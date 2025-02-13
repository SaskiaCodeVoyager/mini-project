<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Tahap;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with( 'tahap')->get();
        $tahaps = Tahap::all();

        return view('projects.index', compact('projects',  'tahaps'));
    }

    public function create()
    {
        $tahaps = Tahap::all();
        return view('projects.create', compact( 'tahaps'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nama_project' => 'required|string|max:255',
            'deskripsi' => 'required',
            'tahap_id' => 'required|exists:tahaps,id',
        ]);

        Project::create($request->all());

        return redirect()->route('projects.index')->with('success', 'Project berhasil ditambahkan');
    }

    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
       
        $tahaps = Tahap::all();
        return view('projects.edit', compact('project', 'tahaps'));
    }

    public function update(Request $request, Project $project)
    {
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
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project berhasil dihapus');
    }
}
