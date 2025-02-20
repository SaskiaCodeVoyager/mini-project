@extends('layouts.app')

@section('content')
<div x-data="{
    openCreateModal: false,
    openEditModal: false,
    openDeleteModal: false,
    projectId: null,
    projectNamaProject: '',
    projectDesc: '',
    projectTahap: null,
    selected: '{{ Auth::id() }}',
    tahapIndividu: {{ $tahaps->where('nama', 'individu')->first()->id ?? 'null' }},
    tahapPremini: {{ $tahaps->where('nama', 'premini')->first()->id ?? 'null' }},
    tahapMini: {{ $tahaps->where('nama', 'mini')->first()->id ?? 'null' }}
}" class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4 text-blue-800">Daftar Project</h2>

    <button @click="openCreateModal = true" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition duration-300">Tambah Project</button>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-6">
        @foreach($projects as $project)
        <div class="bg-blue-100 p-4 rounded-lg shadow-lg border border-blue-200 hover:shadow-xl transition duration-300">
            <h3 class="text-xl font-semibold text-blue-800">{{ $project->nama_project }}</h3>
            <ul>
                <span>Nama Anggota:</span>
                @foreach($project->users as $user)
                    <li>{{ $user->username }}</li>
                @endforeach
            </ul>
            <p class="text-blue-600 text-sm mt-2">Deskripsi: {{ $project->deskripsi }}</p>
            <p class="text-blue-600 text-sm mt-2">Tahap: {{ $project->tahap->nama }}</p>
            
            <div class="mt-4 flex justify-between">
                <button 
                    @click="projectId = {{ $project->id }}; 
                            projectNamaProject = '{{ addslashes($project->nama_project) }}'; 
                            projectTahap = {{ $project->tahap->id ?? 'null' }}; 
                            projectDesc = '{{ addslashes($project->deskripsi) }}'; 
                            openEditModal = true; 
                            $nextTick(() => { 
                                if ($refs.multiNamaEdit) { 
                                    $($refs.multiNamaEdit).val({{ json_encode($project->users->pluck('id_user')->toArray()) }}).trigger('change'); 
                                } 
                            });" 
                    class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition duration-300">
                    Edit
                </button>

                <button @click="projectId = {{ $project->id }}; openDeleteModal = true" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition duration-300">Hapus</button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Create Modal -->
    <div x-show="openCreateModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50" @click.away="openCreateModal = false" x-transition>
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-bold mb-4 text-blue-800">Tambah Project</h2>
            <form action="/projects" method="POST">
                @csrf
                
                <select name="tahap_id" class="w-full p-2 border rounded mb-2 text-blue-800" x-model="projectTahap" required>
                    <option value="" disabled>Pilih Tahap</option>
                    @foreach($tahaps as $tahap)
                        <option value="{{ $tahap->id }}">{{ $tahap->nama }}</option>
                    @endforeach
                </select>
                
                <template x-if="projectTahap == tahapMini">
                    <select x-ref="multiNama" name="nama[]" class="w-full border p-2 select2" multiple="multiple">
                        @foreach($users as $user)
                            <option value="{{ $user->id_user }}">{{ $user->username }}</option>
                        @endforeach
                    </select>
                </template>                
                
                <template x-if="projectTahap == tahapIndividu || projectTahap == tahapPremini">
                    <div>
                        <input type="hidden" name="nama[]" :value="selectedUser  ">
                        <p class="p-2 border rounded bg-gray-100 text-gray-800">{{ Auth::user()->username }}</p>
                    </div>
                </template>
                
                <input type="text" name="nama_project" placeholder="Nama Project" class="w-full p-2 border rounded mb-2 text-blue-800" x-model="projectNamaProject" required>
                <textarea name="deskripsi" placeholder="Deskripsi" class="w-full p-2 border rounded mb-2 text-blue-800" x-model="projectDesc" required></textarea>
                
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition duration-300">Tambah</button>
                <button type="button" @click="openCreateModal = false" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition duration-300">Batal</button>
            </form>
        </div>
    </div>
    
    <!-- Edit Modal -->
  <!-- Edit Modal -->
<div x-show="openEditModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50" 
@click.away="openEditModal = false" x-transition>
<div class="bg-white p-6 rounded-lg shadow-lg w-96">
    <h2 class="text-xl font-bold mb-4 text-blue-800">Edit Project</h2>
    
    <form :action="'{{ route('projects.update', '') }}/' + projectId" method="POST">

        @csrf
        @method('PUT')

        <input type="text" name="nama_project" placeholder="Nama Project" 
            class="w-full p-2 border rounded mb-2" x-model="projectNamaProject" required>

        <textarea name="deskripsi" placeholder="Deskripsi" 
            class="w-full p-2 border rounded mb-2" x-model="projectDesc" required></textarea>

        <select name="tahap_id" class="w-full p-2 border rounded mb-2" x-model="projectTahap" required>
            <option value="" disabled>Pilih Tahap</option>
            @foreach($tahaps as $tahap)
                <option value="{{ $tahap->id }}">{{ $tahap->nama }}</option>
            @endforeach
        </select>

        <select name="nama[]" class="w-full p-2 border rounded mb-2 select2" multiple x-ref="multiNamaEdit">
            @foreach ($users as $user)
                <option value="{{ $user->id_user }}">{{ $user->username }}</option>
            @endforeach
        </select>

        <div class="flex justify-end space-x-2 mt-4">
            <button type="button" @click="openEditModal = false" 
                class="px-4 py-2 bg-gray-500 text-white rounded">Batal</button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
        </div>
    </form>
</div>
</div>


    <!-- Delete Modal -->
    <div x-show="openDeleteModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50" @click.away="openDeleteModal = false" x-transition>
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-bold mb-4 text-red-600">Hapus Project</h2>
            <p class="text-gray-700 mb-4">Apakah kamu yakin ingin menghapus project ini? Tindakan ini tidak bisa dibatalkan!</p>
            
            <form :action="'/projects/' + projectId" method="POST">
                @csrf
                @method('DELETE')
    
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="openDeleteModal = false" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition duration-300">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition duration-300">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("projectComponent", () => ({
            projectTahap: null,
            tahapMini: {{ $tahaps->where('nama', 'mini')->first()->id ?? 'null' }},

            init() {
                this.$watch('projectTahap', (value) => {
                    if (value == this.tahapMini) {
                        this.$nextTick(() => {
                            if (this.$refs.multiNama) {
                                $(this.$refs.multiNama).select2({
                                    placeholder: "Pilih Pengguna",
                                    allowClear: true,
                                    width: '100%',
                                    maximumSelectionLength: 2
                                });
                            }
                        });
                    }
                });
            }
        }));
    });

    document.addEventListener("DOMContentLoaded", function() {
        $(document).on('change', 'select.select2', function () {
            $(this).select2({
                placeholder: "Pilih Pengguna",
                allowClear: true,
                width: '100%',
                maximumSelectionLength: 4
            });
        });
    });

    document.addEventListener("alpine:initialized", function() {
        $(document).on('DOMNodeInserted', 'select.select2', function () {
            $(this).select2({
                placeholder: "Pilih Pengguna",
                allowClear: true,
                width: '100%',
                maximumSelectionLength: 2
            });
        });
    });
</script>

@endsection