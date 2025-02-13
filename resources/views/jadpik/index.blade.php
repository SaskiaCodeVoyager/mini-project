@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">Daftar Jadwal Piket</h1>
    @if(auth()->user()->role === 'admin')
    <button class="bg-blue-500 text-white px-4 py-2 rounded-md mb-3" id="showCreateModal">Tambah Jadpik</button>
    @endif

    @if(session('success'))
        <div class="bg-green-500 text-white p-2 rounded-md mt-3">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-3">
        @foreach($haris as $hari)
            <div class="bg-white rounded-lg shadow-lg p-4">
                <h2 class="text-xl font-semibold mb-4">{{ $hari->nama }}</h2>
                @if($jadpiks->where('hari_id', $hari->id)->isEmpty())
                    <p class="text-gray-500">Tidak ada jadwal piket.</p>
                @else
                    <div class="space-y-2">
                        @foreach ($jadpiks->where('hari_id', $hari->id) as $index => $jadpik)
                            <div class="flex items-center justify-between bg-gray-100 p-2 rounded">
                                <div class="flex items-center">
                                    {{-- <span class="mr-2 font-bold">{{ $index + 1 }}.</span> --}}
                                    <span>{{ $jadpik->nama_siswa }}</span>
                                </div>
                                @if(auth()->user()->role === 'admin')
                                <div class="space-x-2">
                                    <button class="bg-yellow-500 text-white px-3 py-1 rounded-md btn-edit" 
                                        data-id="{{ $jadpik->id }}" 
                                        data-nama="{{ $jadpik->nama_siswa }}" 
                                        data-hari="{{ $jadpik->hari_id }}">
                                        Edit
                                    </button>
                                    <button class="bg-red-500 text-white px-3 py-1 rounded-md btn-delete" 
                                        data-id="{{ $jadpik->id }}">
                                        Hapus
                                    </button>
                                </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@if(auth()->user()->role === 'admin')
<!-- Modal Tambah Jadpik -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h5 class="text-xl font-semibold mb-4">Tambah Jadwal Piket</h5>
        <form action="{{ route('jadpik.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nama Siswa</label>
                <input type="text" class="mt-1 block w-full border-gray-300 rounded-md" name="nama_siswa" placeholder="Masukkan Nama Panjang anda!!">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Hari</label>
                <select class="mt-1 block w-full border-gray-300 rounded-md" name="hari_id" >
                    @foreach($haris as $hari)
                        <option value="{{ $hari->id }}">{{ $hari->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Simpan</button>
                <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded-md ml-2" id="closeCreateModal">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Jadpik -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h5 class="text-xl font-semibold mb-4">Edit Jadwal Piket</h5>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nama Siswa</label>
                <input type="text" id="editNamaSiswa" class="mt-1 block w-full border-gray-300 rounded-md" name="nama_siswa" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Hari</label>
                <select id="editHariId" class="mt-1 block w-full border-gray-300 rounded-md" name="hari_id" required>
                    @foreach($haris as $hari)
                        <option value="{{ $hari->id }}">{{ $hari->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-md">Update</button>
                <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded-md ml-2" id="closeEditModal">Batal</button>
            </div>
        </form>               
    </div>
</div>

<!-- Modal Hapus Jadpik -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h5 class="text-xl font-semibold mb-4">Konfirmasi Hapus</h5>
        <p>Apakah Anda yakin ingin menghapus jadwal piket ini?</p>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-end mt-4">
                <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded-md" id="closeDeleteModal">Batal</button>
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md ml-2">Hapus</button>
            </div>
        </form>                       
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Script jQuery untuk Modal -->
<script>
    $(document).ready(function() {
        // Tampilkan modal tambah
        $('#showCreateModal').click(function() {
            $('#createModal').removeClass('hidden');
        });

        // Tutup modal tambah
        $('#closeCreateModal').click(function() {
            $('#createModal').addClass('hidden');
        });

        // Tampilkan modal edit
        $('.btn-edit').click(function() {
            let id = $(this).data('id');
            let nama_siswa = $(this).data('nama');
            let hari_id = $(this).data('hari');

            $('#editNamaSiswa').val(nama_siswa);
            $('#editHariId').val(hari_id);
            $('#editForm').attr('action', '/jadpik/' + id);
            $('#editModal').removeClass('hidden');
        });

        // Tutup modal edit
        $('#closeEditModal').click(function() {
            $('#editModal').addClass('hidden');
        });

        // Tampilkan modal hapus
        $('.btn-delete').click(function() {
            let id = $(this).data('id');
            $('#deleteForm').attr('action', '/jadpik/' + id);
            $('#deleteModal').removeClass('hidden');
        });

        // Tutup modal hapus
        $('#closeDeleteModal').click(function() {
            $('#deleteModal').addClass('hidden');
        });
    });
</script>
@endif

@endsection
