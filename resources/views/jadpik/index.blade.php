@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .overflow-y-auto::-webkit-scrollbar {
            width: 5px;
        }
        .overflow-y-auto::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 10px;
        }
    </style>    
</head>
<body>

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
                @if($jadpiks[$hari->id]->isEmpty())
                    <p class="text-gray-500">Tidak ada jadwal piket.</p>
                @else
                    <div class="overflow-y-auto max-h-64 space-y-2">
                        @foreach ($jadpiks[$hari->id] as $jadpik)
                            @php
                                $nama_siswa = explode(',', $jadpik->nama_siswa);
                            @endphp
                            <div class="bg-gray-100 p-2 rounded">
                                <span>{{ $nama_siswa[0] }}</span> <!-- Menampilkan hanya nama pertama -->
                                @if(auth()->user()->role === 'admin')
                                <div class="flex space-x-4">
                                    <button class="bg-yellow-500 text-white px-2 py-1 rounded-md btn-edit" 
                                        data-id="{{ $jadpik->id }}" 
                                        data-nama="{{ $jadpik->nama_siswa }}" 
                                        data-hari="{{ $jadpik->hari_id }}">
                                        Edit
                                    </button>
                                    <button class="bg-red-500 text-white px-2 py-1 rounded-md btn-delete" 
                                        data-id="{{ $jadpik->id }}">
                                        Hapus
                                    </button>
                                </div>
                                @endif
                            </div>
                            @if(count($nama_siswa) > 1)
                                @foreach(array_slice($nama_siswa, 1) as $nama)
                                    <div class="bg-gray-100 p-2 rounded">
                                        <span>{{ $nama }}</span>
                                        @if(auth()->user()->role === 'admin')
                                            <div class="flex space-x-4">
                                                <button class="bg-yellow-500 text-white px-2 py-1 rounded-md btn-edit" 
                                                    data-id="{{ $jadpik->id }}" 
                                                    data-nama="{{ $jadpik->nama_siswa }}" 
                                                    data-hari="{{ $jadpik->hari_id }}">
                                                    Edit
                                                </button>
                                                <button class="bg-red-500 text-white px-2 py-1 rounded-md btn-delete" 
                                                    data-id="{{ $jadpik->id }}">
                                                    Hapus
                                                </button>
                                            </div>
                                            @endif
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                    <div class="mt-3">
                        {{ $jadpiks[$hari->id]->links('pagination::tailwind') }}
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
                <select class="select2" name="id_user[]" multiple="multiple">
                    @foreach($users as $user)
                        <option value="{{ $user->id_user }}">{{ $user->username }}</option>
                    @endforeach
                </select>
                
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
@if(auth()->user()->role === 'admin')
<!-- Modal Edit Jadpik -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h5 class="text-xl font-semibold mb-4">Edit Jadwal Piket</h5>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nama Siswa</label>
                <select id="editNamaSiswa" class="select2 w-full" name="id_user[]" multiple="multiple">
                    @foreach($users as $user)
                        <option value="{{ $user->id_user }}">{{ $user->username }}</option>
                    @endforeach
                </select>
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
@endif

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
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<!-- Script jQuery untuk Modal -->
<script>
    $(document).ready(function() {
    $('.select2').select2({
        placeholder: "Pilih Siswa",
        allowClear: true
    });

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
    let nama_siswa = $(this).data('nama').split(', ').map(nama => nama.trim()); // Pastikan pemisahan benar tanpa spasi ekstra
    let hari_id = $(this).data('hari');

    // Cari ID user berdasarkan nama siswa yang dipilih
    let selectedUsers = [];
    $('#editNamaSiswa option').each(function() {
        if (nama_siswa.includes($(this).text())) {
            selectedUsers.push($(this).val());
        }
    });

    $('#editNamaSiswa').val(selectedUsers).trigger('change'); // Memastikan Select2 menerima array dengan benar
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
</body>
</html>
@endsection
