@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Daftar Jurnal</h1>
    
    <!-- Tombol Tambah Jurnal -->
    <div class="mb-4 flex gap-2">
        <button 
            onclick="openModal('create')" 
            class="bg-blue-500 text-white px-4 py-2 rounded"
        >
            Tambah Jurnal
        </button>
    </div>

    <table class="w-full border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border p-2">Judul</th>
                <th class="border p-2">Gambar</th>
                <th class="border p-2">Deskripsi</th>
                <th class="border p-2">Tanggal</th>
                <th class="border p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jurnals as $jurnal)
            <tr>
                <td class="border p-2">{{ $jurnal->judul }}</td>
                <td class="border p-2"><img src="{{ asset('storage/' . $jurnal->gambar) }}" alt="{{ $jurnal->judul }}" width="100"></td>
                <td class="border p-2">{{ $jurnal->deskripsi }}</td>
                <td class="border p-2">
                    {{ \Carbon\Carbon::parse($jurnal->created_at)->locale('id')->translatedFormat('l, d F Y') }}
                </td>
                <td class="border p-2">
                    <button onclick="openModal('show', {{ $jurnal }})" class="text-blue-500">Lihat</button>
                    @if (now()->diffInDays($jurnal->created_at) <= 1)
                        <button onclick="openModal('edit', {{ $jurnal }})" class="text-yellow-500">Edit</button>
                    @endif
                    <button onclick="konfirmasiHapus({{ $jurnal->id }})" class="text-red-500">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="modalHapus" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h2>
        <p>Apakah Anda yakin ingin menghapus jurnal ini?</p>
        <div class="mt-4 flex justify-end">
            <button id="batalHapus" class="px-4 py-2 bg-gray-300 text-gray-700 rounded mr-2">Batal</button>
            <form id="formHapus" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded">Hapus</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded shadow-lg w-96">
        <h2 id="modalTitle" class="text-lg font-bold mb-4"></h2>
        <form id="modalForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="modalMethod">
            <div id="modalBody"></div>
            <div class="mt-4 flex justify-end">
                <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Batal</button>
                <button type="submit" id="modalSubmit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function konfirmasiHapus(jurnalId) {
        const modal = document.getElementById('modalHapus');
        const formHapus = document.getElementById('formHapus');

        if (jurnalId) {
            formHapus.action = `/jurnals/${jurnalId}`;
            modal.classList.remove('hidden');
        }
    }

    document.getElementById('batalHapus').addEventListener('click', function () {
        document.getElementById('modalHapus').classList.add('hidden');
    });

    function openModal(type, data = null) {
        const modal = document.getElementById('modal');
        const title = document.getElementById('modalTitle');
        const body = document.getElementById('modalBody');
        const form = document.getElementById('modalForm');
        const submit = document.getElementById('modalSubmit');

        form.reset();
        body.innerHTML = '';
        submit.classList.remove('hidden');

        if (type === 'create' || type === 'edit') {
            title.innerText = type === 'create' ? 'Tambah Jurnal' : 'Edit Jurnal';
            form.action = type === 'create' ? '{{ route('jurnals.store') }}' : `{{ url('jurnals') }}/${data.id}`;
            if (type === 'edit') {
                document.getElementById('modalMethod').value = 'PUT';
            }

            body.innerHTML = `
                <label class='block mb-2'>Judul</label>
                <input type='text' name='judul' class='border p-2 w-full mb-2' required value="${data ? data.judul : ''}">
                <label class='block mb-2'>Gambar</label>
                <input type='file' name='gambar' class='border p-2 w-full mb-2'>
                <label class='block mb-2'>Deskripsi</label>
                <textarea name='deskripsi' class='border p-2 w-full' required>${data ? data.deskripsi : ''}</textarea>
            `;
        }
        
        modal.classList.remove('hidden');
    }
    
    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
    }
</script>
@endsection
