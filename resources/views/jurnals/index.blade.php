@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-300 rounded">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="text-2xl font-bold mb-4 text-blue-900">Daftar Jurnal</h1>

    <div class="mb-4 flex gap-2">
        <button 
            onclick="openModal('create')" 
            class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-600"
        >
            Tambah Jurnal
        </button>
    </div>

    <table class="w-full border border-blue-200">
        <thead>
            <tr class="bg-blue-100">
                <th class="border p-2 text-blue-900">Judul</th>
                <th class="border p-2 text-blue-900">Gambar</th>
                <th class="border p-2 text-blue-900">Deskripsi</th>
                <th class="border p-2 text-blue-900">Tanggal</th>
                <th class="border p-2 text-blue-900">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jurnals as $jurnal)
            <tr class="bg-white hover:bg-blue-50">
                <td class="border p-2 text-blue-700">{{ $jurnal->judul }}</td>
                <td class="border p-2">
                    <img src="{{ asset('storage/' . $jurnal->gambar) }}" alt="{{ $jurnal->judul }}" width="100">
                </td>
                <td class="border p-2 text-blue-700">{{ $jurnal->deskripsi }}</td>
                <td class="border p-2 text-blue-700">
                    {{ \Carbon\Carbon::parse($jurnal->created_at)->locale('id')->translatedFormat('l, d F Y') }}
                </td>
                <td class="border p-2 flex gap-2">
                    <button onclick="showDetail({{ json_encode($jurnal) }})" class="text-blue-500 hover:text-blue-400">
                        <i class="fas fa-eye"></i> Show
                    </button>
                    <form action="{{ route('jurnals.destroy', $jurnal->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jurnal ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-400">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Show -->
<div id="modalShow" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded shadow-lg w-96">
        <h2 class="text-lg font-bold mb-4 text-blue-900">Detail Jurnal</h2>
        <div id="showBody"></div>
        <div class="mt-4 flex justify-end">
            <button type="button" onclick="closeShowModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Tutup</button>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div id="modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded shadow-lg w-96">
        <h2 id="modalTitle" class="text-lg font-bold mb-4 text-blue-900"></h2>
        <form id="modalForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="modalMethod">
            <div id="modalBody"></div>
            <div class="mt-4 flex justify-end">
                <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Batal</button>
                <button type="submit" id="modalSubmit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(type, data = null) {
        const modal = document.getElementById('modal');
        const title = document.getElementById('modalTitle');
        const body = document.getElementById('modalBody');
        const form = document.getElementById('modalForm');

        form.reset();
        body.innerHTML = '';

        if (type === 'create') {
            title.innerText = 'Tambah Jurnal';
            form.action = '{{ route('jurnals.store') }}';
            body.innerHTML = `
                <label class='block mb-2'>Judul</label>
                <input type='text' name='judul' class='border p-2 w-full mb-2' required>
                <label class='block mb-2'>Gambar</label>
                <input type='file' name='gambar' class='border p-2 w-full mb-2'>
                <label class='block mb-2'>Deskripsi (min 150 karakter)</label>
                <textarea name='deskripsi' class='border p-2 w-full' required oninput="updateCharacterCount(this)"></textarea>
                <div id="charCount" class="text-gray-500">0/150</div>
            `;
        }
        
        modal.classList.remove('hidden');
    }

    function showDetail(data) {
    console.log("Show Detail Called:", data); // Tambahan untuk debugging
    const modalShow = document.getElementById('modalShow');
    const showBody = document.getElementById('showBody');

    showBody.innerHTML = `
        <strong>Nama:</strong>
        <p> {{ $jurnal->user ? $jurnal->user->username : 'Tidak Diketahui' }}</p>
        <strong>Asal Sekolah:</strong>
        <p> {{ $jurnal->user ? $jurnal->user->asal_sekolah : 'Tidak Diketahui' }}</p>
        <strong>Kegiatan:</strong>
        <p> ${data.deskripsi}</p>
        <strong>Tanggal:</strong>
       <p> ${new Date(data.created_at).toISOString().replace('T', ' ').split('.')[0]}</p>
        <strong>Bukti:</strong>
        <img src="${data.gambar ? '{{ asset('storage/') }}' + '/' + data.gambar : ''}" alt="${data.judul}" width="100">
    `;

    modalShow.classList.remove('hidden');
}

    function closeShowModal() {
        document.getElementById('modalShow').classList.add('hidden');
    }

    function updateCharacterCount(textarea) {
        const charCount = document.getElementById('charCount');
        const currentLength = textarea.value.length;
        charCount.innerText = `${currentLength}/150`;

        if (currentLength < 150) {
            charCount.classList.add('text-red-500');
            charCount.classList.remove('text-green-500');
        } else {
            charCount.classList.remove('text-red-500');
            charCount.classList.add('text-green-500');
        }
    }

    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
    }
</script>
@endsection