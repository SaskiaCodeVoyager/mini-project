@extends('layouts.app')

@section('content')
<div x-data="{
    openCreateModal: false,
    openEditModal: false,
    openDeleteModal: false,
    projectId: null,
    projectNama: '',
    projectNamaProject: '',
    projectTahap: '',
    projectDesc: ''
}" class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Daftar Project</h2>

    <button @click="openCreateModal = true" class="px-4 py-2 bg-blue-500 text-white rounded">Tambah Project</button>

    <table class="table-auto w-full mt-4 border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">Nama</th>
                <th class="border px-4 py-2">Nama Project</th>
                <th class="border px-4 py-2">Deskripsi</th>
                <th class="border px-4 py-2">Tahap</th>
                <th class="border px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $project)
            <tr class="text-center">
                <td class="border px-4 py-2">{{ $project->nama }}</td>
                <td class="border px-4 py-2">{{ $project->nama_project }}</td>
                <td class="border px-4 py-2">{{ $project->deskripsi }}</td>
                <td class="border px-4 py-2">{{ $project->tahap->nama }}</td>
                <td class="border px-4 py-2">
                    <button @click="
                        projectId = {{ $project->id }};
                        projectNama = '{{ $project->nama }}';
                        projectNamaProject = '{{ $project->nama_project }}';
                        projectTahap = '{{ $project->tahap->id }}';
                        projectDesc = '{{ $project->deskripsi }}';
                        openEditModal = true
                    " class="px-2 py-1 bg-yellow-500 text-white rounded">Edit</button>

                    <button @click="projectId = {{ $project->id }}; openDeleteModal = true" class="px-2 py-1 bg-red-500 text-white rounded">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Tambah Project -->
    <div x-show="openCreateModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-bold mb-4">Tambah Project</h2>
            <form action="/projects" method="POST">
                @csrf
                <input type="text" name="nama" placeholder="Nama" class="w-full p-2 border rounded mb-2" x-model="projectNama" required>
                <input type="text" name="nama_project" placeholder="Nama Project" class="w-full p-2 border rounded mb-2" x-model="projectNamaProject" required>

                <!-- Dropdown Tahap -->
                <select name="tahap_id" class="w-full p-2 border rounded mb-2" x-model="projectTahap" required>
                    <option value="" disabled>Pilih Tahap</option>
                    @foreach($tahaps as $tahap)
                        <option value="{{ $tahap->id }}">{{ $tahap->nama }}</option>
                    @endforeach
                </select>

                <textarea name="deskripsi" placeholder="Deskripsi" class="w-full p-2 border rounded mb-2" x-model="projectDesc" required></textarea>

                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Tambah</button>
                <button type="button" @click="openCreateModal = false" class="px-4 py-2 bg-gray-500 text-white rounded">Batal</button>
            </form>
        </div>
    </div>

    <!-- Modal Edit Project -->
    <div x-show="openEditModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-bold mb-4">Edit Project</h2>
            <form :action="'/projects/' + projectId" method="POST">
                @csrf
                @method('PUT')
                <input type="text" name="nama" placeholder="Nama" class="w-full p-2 border rounded mb-2" x-model="projectNama" required>
                <input type="text" name="nama_project" placeholder="Nama Project" class="w-full p-2 border rounded mb-2" x-model="projectNamaProject" required>

                <!-- Dropdown Tahap -->
                <select name="tahap_id" class="w-full p-2 border rounded mb-2" x-model="projectTahap" required>
                    <option value="" disabled>Pilih Tahap</option>
                    @foreach($tahaps as $tahap)
                        <option value="{{ $tahap->id }}">{{ $tahap->nama }}</option>
                    @endforeach
                </select>

                <textarea name="deskripsi" placeholder="Deskripsi" class="w-full p-2 border rounded mb-2" x-model="projectDesc" required></textarea>

                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Simpan</button>
                <button type="button" @click="openEditModal = false" class="px-4 py-2 bg-gray-500 text-white rounded">Batal</button>
            </form>
        </div>
    </div>

    <!-- Modal Hapus Project -->
    <div x-show="openDeleteModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
            <h2 class="text-xl font-bold mb-4">Konfirmasi Hapus</h2>
            <p>Apakah Anda yakin ingin menghapus project ini?</p>
            <form :action="'/projects/' + projectId" method="POST" class="mt-4">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded">Hapus</button>
                <button type="button" @click="openDeleteModal = false" class="px-4 py-2 bg-gray-500 text-white rounded">Batal</button>
            </form>
        </div>
    </div>
</div>
@endsection
