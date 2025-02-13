@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">Daftar Divisi</h1>
    <button class="bg-blue-500 text-white px-4 py-2 rounded-md mb-3" id="showCreateModal">Tambah Divisi</button><br>

    @if(session('error'))
    <div class="bg-red-500 text-white p-3 rounded-md mb-4">
        {{ session('error') }}
    </div>
    @endif

    @if(session('success'))
        <div class="bg-green-500 text-white p-3 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif



    @if(session('success'))
        <div class="bg-green-500 text-white p-3 rounded-md mb-4">
            {{ session('success') }}
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
                <th class="px-4 py-2 border">No</th>
                <th class="px-4 py-2 border">Nama Divisi</th>
                <th class="px-4 py-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($divisis as $index => $divisi)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                    <td class="px-4 py-2 border">{{ $divisi->nama }}</td>
                    <td class="px-4 py-2 border">
                        <button class="bg-yellow-500 text-white px-4 py-2 rounded-md btn-edit" data-id="{{ $divisi->id }}" data-nama="{{ $divisi->nama }}">Edit</button>
                        <button type="button" class="bg-red-500 text-white px-4 py-2 rounded-md ml-2 btn-delete" data-id="{{ $divisi->id }}">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Tambah Divisi -->
<!-- Modal Tambah Divisi -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h5 class="text-xl font-semibold mb-4">Tambah Divisi</h5>
        <form action="{{ route('divisi.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Divisi</label>
                <input type="text" class="form-input mt-1 block w-full border-gray-300 rounded-md @error('nama') border-red-500 @enderror" 
                       id="nama" name="nama" value="{{ old('nama') }}" >
                
                <!-- Show error if any -->
                @error('nama')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Simpan</button>
            <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded-md mt-3 ml-2" id="closeCreateModal">Batal</button>
        </form>
    </div>
</div>


<!-- Modal Edit Divisi -->
<!-- Modal Edit Divisi -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h5 class="text-xl font-semibold mb-4">Edit Divisi</h5>
        <form id="editForm" action="{{ url('divisi') }}/{{ old('id', $divisi->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="editNama" class="block text-sm font-medium text-gray-700">Nama Divisi</label>
                <input type="text" class="form-input mt-1 block w-full border-gray-300 rounded-md @error('nama') border-red-500 @enderror" 
                       id="editNama" name="nama" value="{{ old('nama', $divisi->nama) }}" required>
                
                <!-- Show error if any -->
                @error('nama')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-md">Update</button>
            <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded-md mt-3 ml-2" id="closeEditModal">Batal</button>
        </form>               
    </div>
</div>


<!-- Modal Konfirmasi Hapus Divisi -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h5 class="text-xl font-semibold mb-4">Konfirmasi Hapus</h5>
        <p>Apakah Anda yakin ingin menghapus divisi ini?</p>
        <div class="mt-4">
            <form id="deleteForm" action="{{ url('divisi') }}/{{ $divisi->id }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded-md" id="closeDeleteModal">Batal</button>
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md ml-2">Hapus</button>
            </form>                       
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Script jQuery untuk Modal -->
<script>
    $(document).ready(function() {
        // Menampilkan modal tambah
        $('#showCreateModal').click(function() {
            $('#createModal').removeClass('hidden');
        });

        // Menutup modal tambah
        $('#closeCreateModal').click(function() {
            $('#createModal').addClass('hidden');
        });

        // Menampilkan modal edit dan mengisi data
    $('.btn-edit').click(function() {
        let id = $(this).data('id');
        let nama = $(this).data('nama');
        $('#editNama').val(nama);
        $('#editForm').attr('action', '/divisi/' + id);  // Pastikan action form mengarah ke divisi/id yang tepat
        $('#editModal').removeClass('hidden');
    });

    // Menutup modal edit
    $('#closeEditModal').click(function() {
        $('#editModal').addClass('hidden');
    });

    // Menampilkan modal hapus dan mengisi data
    $('.btn-delete').click(function() {
        let id = $(this).data('id');
        $('#deleteForm').attr('action', '/divisi/' + id);  // Pastikan action form mengarah ke divisi/id yang tepat
        $('#deleteModal').removeClass('hidden');
    });

    // Menutup modal hapus
    $('#closeDeleteModal').click(function() {
        $('#deleteModal').addClass('hidden');
    });
    });
</script>

@endsection
