@extends('layouts.app')

@section('content')
<div x-data="{ openModal: false, openDetailModal: false, selectedIzin: null, deleteUrl: '', openDeleteModal: false }" class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-blue-900">Daftar Absensi dan Izin</h1>

    <!-- Menampilkan pesan sukses dan error -->
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tombol Absen dan Izin di atas tabel -->
    <div class="mt-4 flex gap-4 mb-4">
        <form action="{{ route('absens.store') }}" method="POST">
            @csrf
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Absen Sekarang</button>
        </form>

        <button @click="openModal = true" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Ajukan Izin</button>
    </div>

    <!-- Tabel Absensi -->
    <h2 class="text-xl font-semibold mt-4 mb-2 text-blue-800">Daftar Absensi</h2>
    <table class="w-full border-collapse border border-blue-200">
        <thead>
            <tr class="bg-blue-900 text-white">
                <th class="border border-blue-300 px-4 py-2">Nama</th>
                <th class="border border-blue-300 px-4 py-2">Tanggal</th>
                <th class="border border-blue-300 px-4 py-2">Keterangan</th>
                <th class="border border-blue-300 px-4 py-2">Absen Masuk</th>
                <th class="border border-blue-300 px-4 py-2">Absen Pulang</th>
                <th class="border border-blue-300 px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($absens as $absen)
            <tr class="hover:bg-blue-50">
                <td class="border border-blue-300 px-4 py-2">{{ $absen->user->username }}</td>
                <td class="border border-blue-300 px-4 py-2">
                    {{ \Carbon\Carbon::parse($absen->tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                </td>
                <td class="border border-blue-300 px-4 py-2">
                    @if($absen->keterangan == 'alpa')
                        <span class="text-blue-600 font-bold">Alpa</span>
                    @elseif($absen->keterangan == 'izin')
                        <span class="text-blue-600 font-bold">Izin</span>
                    @else
                        <span class="text-blue-700">Masuk</span>
                    @endif
                </td>
                <td class="border border-blue-300 px-4 py-2">
                    {{ $absen->absen_masuk ? \Carbon\Carbon::parse($absen->absen_masuk)->setTimezone('Asia/Jakarta')->translatedFormat('H:i:s') : '-' }}
                </td>
                <td class="border border-blue-300 px-4 py-2">
                    {{ $absen->absen_pulang ?? '-' }}
                </td>
                <td class="border border-blue-300 px-4 py-2">
                    <button @click="openDeleteModal = true; deleteUrl = '{{ route('absens.destroy', $absen->id) }}'" class="text-red-500 hover:text-red-600">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tabel Izin -->
    <h2 class="text-xl font-semibold mt-8 mb-2 text-blue-800">Daftar Izin</h2>
    <table class="w-full border-collapse border border-blue-200">
        <thead>
            <tr class="bg-blue-900 text-white">
                <th class="border border-blue-300 px-4 py-2">Nama</th>
                <th class="border border-blue-300 px-4 py-2">Tanggal Mulai</th>
                <th class="border border-blue-300 px-4 py-2">Tanggal Selesai</th>
                <th class="border border-blue-300 px-4 py-2">Keterangan</th>
                <th class="border border-blue-300 px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($izins as $izin)
            <tr class="hover:bg-blue-50 bg-blue-100">
                
                <td class="border border-blue-300 px-4 py-2">{{ $izin->user->username }}</td>
                <td class="border border-blue-300 px-4 py-2">
                    {{ \Carbon\Carbon::parse($izin->dari_tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                </td>
                <td class="border border-blue-300 px-4 py-2">
                    {{ \Carbon\Carbon::parse($izin->sampai_tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                </td>
                
                <td class="border border-blue-300 px-4 py-2">
                    <span class="text-blue-600 font-bold">Izin</span>
                </td>
                <td class="border border-blue-300 px-4 py-2">
                    @if(auth()->user() && auth()->user()->role === 'admin')
                        <!-- Tombol Approve untuk Admin -->
                        <form action="{{ route('izins.update', $izin->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="text-green-500 hover:text-green-600">Approve</button>
                        </form>
                    @endif
                    <button @click="selectedIzin = {{ json_encode($izin) }}; openDetailModal = true" class="text-blue-500 hover:text-blue-600">Detail</button> |
                    <button @click="openDeleteModal = true; deleteUrl = '{{ route('izins.destroy', $izin->id) }}'" class="text-red-500 hover:text-red-600">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Form Izin -->
    <div x-cloak x-show="openModal" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform scale-50" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-50" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-blue-50 p-6 rounded-lg w-96 shadow-lg" @click.outside="openModal = false">
            <h2 class="text-xl font-bold mb-4 text-blue-800">Form Izin</h2>
            <form action="{{ route('izins.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block font-semibold text-blue-700">Dari Tanggal</label>
                    <input type="date" name="dari_tanggal" required class="w-full p-2 border rounded-lg bg-blue-100">
                </div>

                <div class="mb-4">
                    <label class="block font-semibold text-blue-700">Sampai Tanggal</label>
                    <input type="date" name="sampai_tanggal" required class="w-full p-2 border rounded-lg bg-blue-100">
                </div>

                <div class="mb-4">
                    <label class="block font-semibold text-blue-700">Bukti (Gambar)</label>
                    <input type="file" name="bukti" required class="w-full p-2 border rounded-lg bg-blue-100">
                </div>

                <div class="mb-4">
                    <label class="block font-semibold text-blue-700">Deskripsi</label>
                    <textarea name="deskripsi" required class="w-full p-2 border rounded-lg bg-blue-100"></textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="openModal = false" class="px-4 py-2 bg-gray-500 text-white rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Ajukan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div x-cloak x-show="openDeleteModal" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform scale-50" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-50" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white p-6 rounded-lg w-96 shadow-lg">
            <h2 class="text-xl font-bold mb-4 text-red-600">Konfirmasi Hapus</h2>
            <p class="mb-4">Apakah Anda yakin ingin menghapus data ini?</p>
            <div class="flex justify-end gap-2">
                <button @click="openDeleteModal = false" class="px-4 py-2 bg-gray-500 text-white rounded">Batal</button>
                <form action="" method="POST" :action="deleteUrl" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Hapus</button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
