@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">Daftar Divisi</h1>
    <button class="bg-blue-500 text-white px-4 py-2 rounded-md mb-3" id="openCreateModal">Tambah Divisi</button>

    @if(session('success'))
        <div class="bg-green-500 text-white p-3 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-3 rounded-md mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-500 text-white p-3 rounded-md mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table class="table-auto w-full mt-3 border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 border">#</th>
                <th class="px-4 py-2 border">Nama Divisi</th>
                <th class="px-4 py-2 border">Deskripsi</th>
                <th class="px-4 py-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($divisis as $divisi)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 border">{{ $divisi->nama }}</td>
                    <td class="px-4 py-2 border">{{ $divisi->deskripsi }}</td>
                    <td class="px-4 py-2 border">
                        <button type="button" 
                                class="bg-yellow-500 text-white px-4 py-2 rounded-md btn-edit" 
                                data-id="{{ $divisi->id }}" 
                                data-nama="{{ $divisi->nama }}"
                                data-deskripsi="{{ $divisi->deskripsi }}">
                            Edit
                        </button>

                        <button type="button" 
                                class="bg-red-500 text-white px-4 py-2 rounded-md ml-2 btn-delete" 
                                data-id="{{ $divisi->id }}">
                            Hapus
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Create Divisi -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden" id="createDivisiModal">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96 relative">
        <h5 class="text-xl font-semibold mb-4">Tambah Divisi</h5>

        <form action="{{ route('divisi.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Divisi</label>
                <input type="text" 
                       class="mt-1 block w-full border-gray-300 rounded-md @error('nama') border-red-500 @enderror" 
                       id="nama" 
                       name="nama" value="{{ old('nama', '') }}">

                @error('nama')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <input type="text" 
                       class="mt-1 block w-full border-gray-300 rounded-md @error('deskripsi') border-red-500 @enderror" 
                       id="deskripsi" 
                       name="deskripsi" value="{{ old('deskripsi', '') }}">

                @error('deskripsi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Simpan</button>
                <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded-md ml-2" id="closeCreateModal">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Divisi -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden" id="editDivisiModal">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96 relative">
        <h5 class="text-xl font-semibold mb-4">Edit Divisi</h5>

        <form id="editDivisiForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="edit-nama" class="block text-sm font-medium text-gray-700">Nama Divisi</label>
                <input type="text" 
                       class="mt-1 block w-full border-gray-300 rounded-md @error('nama') border-red-500 @enderror" 
                       id="edit-nama" 
                       name="nama" required>

                @error('nama')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="edit-deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <input type="text" 
                       class="mt-1 block w-full border-gray-300 rounded-md @error('deskripsi') border-red-500 @enderror" 
                       id="edit-deskripsi" 
                       name="deskripsi" required>

                @error('deskripsi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Update</button>
                <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded-md ml-2" id="closeEditModal">Batal</button>
            </div>
        </form>
    </div>
</div>

<div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden" id="deleteDivisiModal">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96 relative">
        <h5 class="text-xl font-semibold mb-4">Konfirmasi Hapus Divisi</h5>
        <p>Apakah Anda yakin ingin menghapus divisi ini?</p>

        <div class="flex justify-end mt-4">
            <form id="deleteDivisiForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md">Hapus</button>
                <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded-md ml-2" id="closeDeleteModal">Batal</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Open create divisi modal
    document.getElementById('openCreateModal').addEventListener('click', function() {
        document.getElementById('createDivisiModal').classList.remove('hidden');
    });

    // Close create divisi modal
    document.getElementById('closeCreateModal').addEventListener('click', function() {
        document.getElementById('createDivisiModal').classList.add('hidden');
    });

    // Open edit divisi modal with pre-filled data
    document.querySelectorAll('.btn-edit').forEach(function(button) {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');
            const deskripsi = this.getAttribute('data-deskripsi');

            document.getElementById('edit-nama').value = nama;
            document.getElementById('edit-deskripsi').value = deskripsi;
            document.getElementById('editDivisiForm').action = `/divisi/${id}`;
            document.getElementById('editDivisiModal').classList.remove('hidden');
        });
    });

    // Close edit divisi modal
    document.getElementById('closeEditModal').addEventListener('click', function() {
        document.getElementById('editDivisiModal').classList.add('hidden');
    });

    document.querySelectorAll('.btn-delete').forEach(function(button) {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            
            // Set the form action for delete
            document.getElementById('deleteDivisiForm').action = `/divisi/${id}`;

            // Show the delete modal
            document.getElementById('deleteDivisiModal').classList.remove('hidden');
        });
    });

    // Close delete divisi modal
    document.getElementById('closeDeleteModal').addEventListener('click', function() {
        document.getElementById('deleteDivisiModal').classList.add('hidden');
    });
</script>

@endsection
