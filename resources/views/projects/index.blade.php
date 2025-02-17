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
    <h2 class="text-2xl font-bold mb-4 text-blue-800">Daftar Project</h2>

    <button @click="openCreateModal = true" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition duration-300">Tambah Project</button>

    <!-- Card Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-6">
        @foreach($projects as $project)
        <div class="bg-blue-100 p-4 rounded-lg shadow-lg border border-blue-200 hover:shadow-xl transition duration-300">
            <h3 class="text-xl font-semibold text-blue-800">{{ $project->nama_project }}</h3>
            <p class="text-blue-700">{{ $project->nama }}</p>
            <p class="text-blue-600 text-sm mt-2">{{ $project->deskripsi }}</p>
            <p class="text-blue-600 text-sm mt-2">{{ $project->tahap->nama }}</p>
            
            <div class="mt-4 flex justify-between">
                <button @click="projectId = {{ $project->id }}; projectNama = '{{ $project->nama }}'; projectNamaProject = '{{ $project->nama_project }}'; projectTahap = '{{ $project->tahap->id }}'; projectDesc = '{{ $project->deskripsi }}'; openEditModal = true" class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition duration-300">Edit</button>
                <button @click="projectId = {{ $project->id }}; openDeleteModal = true" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition duration-300">Hapus</button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Modal Tambah Project -->
    <div x-show="openCreateModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-bold mb-4 text-blue-800">Tambah Project</h2>
            <form action="/projects" method="POST">
                @csrf
                <input type="text" name="nama" placeholder="Nama" class="w-full p-2 border rounded mb-2 text-blue-800" x-model="projectNama" required>
                <input type="text" name="nama_project" placeholder="Nama Project" class="w-full p-2 border rounded mb-2 text-blue-800" x-model="projectNamaProject" required>

                <!-- Dropdown Tahap -->
                <select name="tahap_id" class="w-full p-2 border rounded mb-2 text-blue-800" x-model="projectTahap" required>
                    <option value="" disabled>Pilih Tahap</option>
                    @foreach($tahaps as $tahap)
                        <option value="{{ $tahap->id }}">{{ $tahap->nama }}</option>
                    @endforeach
                </select>

                <textarea name="deskripsi" placeholder="Deskripsi" class="w-full p-2 border rounded mb-2 text-blue-800" x-model="projectDesc" required></textarea>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition duration-300">Tambah</button>
                <button type="button" @click="openCreateModal = false" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition duration-300">Batal</button>
            </form>
        </div>
    </div>

    <!-- Modal Edit Project -->
    <div x-show="openEditModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-bold mb-4 text-blue-800">Edit Project</h2>
            <form :action="'/projects/' + projectId" method="POST">
                @csrf
                @method('PUT')
                <input type="text" name="nama" placeholder="Nama" class="w-full p-2 border rounded mb-2 text-blue-800" x-model="projectNama" required>
                <input type="text" name="nama_project" placeholder="Nama Project" class="w-full p-2 border rounded mb-2 text-blue-800" x-model="projectNamaProject" required>

                <!-- Dropdown Tahap -->
                <select name="tahap_id" class="w-full p-2 border rounded mb-2 text-blue-800" x-model="projectTahap" required>
                    <option value="" disabled>Pilih Tahap</option>
                    @foreach($tahaps as $tahap)
                        <option value="{{ $tahap->id }}">{{ $tahap->nama }}</option>
                    @endforeach
                </select>

                <textarea name="deskripsi" placeholder="Deskripsi" class="w-full p-2 border rounded mb-2 text-blue-800" x-model="projectDesc" required></textarea>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition duration-300">Simpan</button>
                <button type="button" @click="openEditModal = false" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition duration-300">Batal</button>
            </form>
        </div>
    </div>

    <!-- Modal Hapus Project -->
    <div x-show="openDeleteModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
            <h2 class="text-xl font-bold mb-4 text-blue-800">Konfirmasi Hapus</h2>
            <p>Apakah Anda yakin ingin menghapus project ini?</p>
            <form :action="'/projects/' + projectId" method="POST" class="mt-4">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition duration-300">Hapus</button>
                <button type="button" @click="openDeleteModal = false" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition duration-300">Batal</button>
            </form>
        </div>
    </div>
</div>
@endsection
