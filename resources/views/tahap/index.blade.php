@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">Daftar Tahap</h1>
    <!-- Tombol untuk membuka modal create -->
    <button class="bg-blue-500 text-white px-4 py-2 rounded-md mb-3" id="openCreateModal">Tambah Tahap</button>

    @if(session('success'))
        <div class="bg-green-500 text-white p-3 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif

     <!-- Display global validation errors -->
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
                <th class="px-4 py-2 border">Nama Tahap</th>
                <th class="px-4 py-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tahaps as $tahap)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 border">{{ $tahap->nama }}</td>
                    <td class="px-4 py-2 border">
                        <!-- Tombol Edit untuk membuka modal edit -->
                        <button type="button" 
                                class="bg-yellow-500 text-white px-4 py-2 rounded-md btn-edit" 
                                data-id="{{ $tahap->id }}" 
                                data-nama="{{ $tahap->nama }}">
                            Edit
                        </button>

                        <!-- Tombol Hapus untuk membuka modal konfirmasi hapus -->
                        <button type="button" 
                                class="bg-red-500 text-white px-4 py-2 rounded-md ml-2 btn-delete" 
                                data-id="{{ $tahap->id }}">

                            Hapus
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Create Tahap -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden" id="createModal">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96 relative">
        <h5 class="text-xl font-semibold mb-4">Tambah Tahap</h5>

        <form action="{{ route('tahap.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Tahap</label>
                <input type="text" 
                       class="mt-1 block w-full border-gray-300 rounded-md @error('nama') border-red-500 @enderror" 
                       id="nama" 
                       name="nama" value="{{ old('nama', '') }}">

                <!-- Show error if any for 'nama' -->
                @error('nama')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Simpan</button>
                <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded-md ml-2" id="cancelCreateModal">Batal</button>
            </div>
        </form>
    </div>
</div>


<!-- Modal Edit Tahap -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden" id="editModal">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96 relative">
        <h5 class="text-xl font-semibold mb-4">Edit Tahap</h5>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="editNama" class="block text-sm font-medium text-gray-700">Nama Tahap</label>
                <input type="text" 
                       class="mt-1 block w-full border-gray-300 rounded-md @error('nama') border-red-500 @enderror" 
                       id="editNama" 
                       name="nama" 
                       required value="{{ old('nama') }}">

                <!-- Show error if any for 'editNama' -->
                @error('nama')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-md">Update</button>
                <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded-md ml-2" id="cancelEditModal">Batal</button>
            </div>
        </form>
    </div>
</div>


<!-- Modal Konfirmasi Hapus Tahap -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden" id="deleteModal">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96 relative">
        <h5 class="text-xl font-semibold mb-4">Konfirmasi Hapus</h5>
        <p>Apakah Anda yakin ingin menghapus tahap ini?</p>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-end mt-4">
                <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded-md" id="cancelDeleteModal">Batal</button>
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md ml-2">Hapus</button>
            </div>
        </form>
    </div>
</div>

<!-- jQuery (optional, jika belum ada) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Buka modal create saat tombol "Tambah Tahap" diklik
        $('#openCreateModal').click(function() {
            $('#createModal').removeClass('hidden');
        });

        // Buka modal edit dan isi data yang sesuai
        $('.btn-edit').click(function() {
            let id   = $(this).data('id');
            let nama = $(this).data('nama');
            $('#editNama').val(nama);
            $('#editForm').attr('action', '/tahap/' + id);
            $('#editModal').removeClass('hidden');
        });

        // Buka modal delete dan atur action form-nya
        $('.btn-delete').click(function() {
            let id = $(this).data('id');
            $('#deleteForm').attr('action', '/tahap/' + id);
            $('#deleteModal').removeClass('hidden');
        });

        // Tombol batal untuk menutup modal
        $('#cancelCreateModal').click(function() {
            $('#createModal').addClass('hidden');
        });
        $('#cancelEditModal').click(function() {
            $('#editModal').addClass('hidden');
        });
        $('#cancelDeleteModal').click(function() {
            $('#deleteModal').addClass('hidden');
        });
    });
</script>
@endsection
