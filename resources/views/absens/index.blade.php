@extends('layouts.app')

@section('content')
<div x-data="{ openModal: false, openDetailModal: false, selectedIzin: null, deleteUrl: '', openDeleteModal: false }" class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Daftar Absensi</h1>

    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="border border-gray-300 px-4 py-2">Nama</th>
                <th class="border border-gray-300 px-4 py-2">Tanggal</th>
                <th class="border border-gray-300 px-4 py-2">Keterangan</th>
                <th class="border border-gray-300 px-4 py-2">Absen Masuk</th>
                <th class="border border-gray-300 px-4 py-2">Absen Pulang</th>
                <th class="border border-gray-300 px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($absens as $absen)
            <tr class="hover:bg-gray-100">
                <td class="border border-gray-300 px-4 py-2">{{ $absen->user->username }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    {{ \Carbon\Carbon::parse($absen->tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                </td>
                <td class="border border-gray-300 px-4 py-2">
                    @if($absen->keterangan == 'alpa')
                        <span class="text-red-500 font-bold">Alpa</span>
                    @elseif($absen->keterangan == 'izin')
                        <span class="text-yellow-500 font-bold">Izin</span>
                    @else
                        <span class="text-green-500">Masuk</span>
                    @endif
                </td>
                <td class="border border-gray-300 px-4 py-2">
                    {{ $absen->absen_masuk ? \Carbon\Carbon::parse($absen->absen_masuk)->setTimezone('Asia/Jakarta')->translatedFormat('H:i:s') : '-' }}
                </td>
                <td class="border border-gray-300 px-4 py-2">
                    {{ $absen->absen_pulang ?? '-' }}
                </td>
                <td class="border border-gray-300 px-4 py-2">
                    {{-- <a href="{{ route('absens.edit', $absen->id) }}" class="text-blue-500">Edit</a> | --}}
                    <button @click="openDeleteModal = true; deleteUrl = '{{ route('absens.destroy', $absen->id) }}'" class="text-red-500">Hapus</button>
                </td>
            </tr>
            @endforeach

            @foreach ($izins as $izin)
            <tr class="hover:bg-gray-100 bg-yellow-100">
                <td class="border border-gray-300 px-4 py-2">{{ $izin->user->username }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    {{ \Carbon\Carbon::parse($izin->dari_tanggal)->locale('id')->translatedFormat('l, d F Y') }} 
                    - 
                    {{ \Carbon\Carbon::parse($izin->sampai_tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                </td>
                <td class="border border-gray-300 px-4 py-2">
                    <span class="text-yellow-500 font-bold">Izin</span>
                </td>
                <td class="border border-gray-300 px-4 py-2">-</td>
                <td class="border border-gray-300 px-4 py-2">-</td>
                <td class="border border-gray-300 px-4 py-2">
                    <button @click="selectedIzin = {{ json_encode($izin) }}; openDetailModal = true" class="text-blue-500">Detail</button> |
                    <button @click="openDeleteModal = true; deleteUrl = '{{ route('izins.destroy', $izin->id) }}'" class="text-red-500">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4 flex gap-4">
        <form action="{{ route('absens.store') }}" method="POST">
            @csrf
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Absen Sekarang</button>
        </form>

        <button @click="openModal = true" class="bg-yellow-500 text-white px-4 py-2 rounded">Ajukan Izin</button>
    </div>

    <!-- Modal Form Izin -->
    <div x-cloak x-show="openModal" x-transition class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white p-6 rounded-lg w-96 shadow-lg" @click.outside="openModal = false">
            <h2 class="text-xl font-bold mb-4">Form Izin</h2>
            <form action="{{ route('izins.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-2">
                    <label class="block font-semibold">Dari Tanggal</label>
                    <input type="date" name="dari_tanggal" required class="w-full p-2 border rounded">
                </div>

                <div class="mb-2">
                    <label class="block font-semibold">Sampai Tanggal</label>
                    <input type="date" name="sampai_tanggal" required class="w-full p-2 border rounded">
                </div>

                <div class="mb-2">
                    <label class="block font-semibold">Bukti (Gambar)</label>
                    <input type="file" name="bukti" required class="w-full p-2 border rounded">
                </div>

                <div class="mb-4">
                    <label class="block font-semibold">Deskripsi</label>
                    <textarea name="deskripsi" required class="w-full p-2 border rounded"></textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="openModal = false" class="px-4 py-2 bg-gray-500 text-white rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Kirim</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detail Izin -->
    <div x-cloak x-show="openDetailModal" x-transition class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white p-6 rounded-lg w-96 shadow-lg" @click.outside="openDetailModal = false">
            <h2 class="text-xl font-bold mb-4">Detail Izin</h2>

            <div x-show="selectedIzin !== null">
                <div class="mb-2">
                    <label class="block font-semibold">Nama</label>
                    <p x-text="selectedIzin.user.username"></p>
                </div>
                <div class="mb-2">
                    <label class="block font-semibold">Dari Tanggal</label>
                    <p x-text="selectedIzin.dari_tanggal"></p>
                </div>
                <div class="mb-2">
                    <label class="block font-semibold">Sampai Tanggal</label>
                    <p x-text="selectedIzin.sampai_tanggal"></p>
                </div>
                <div class="mb-2">
                    <label class="block font-semibold">Deskripsi</label>
                    <p x-text="selectedIzin.deskripsi"></p>
                </div>
                <div class="mb-2">
                    <label class="block font-semibold">Bukti</label>
                    <img :src="'{{ asset('storage') }}/' + selectedIzin.bukti" alt="Bukti Izin">


                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" @click="openDetailModal = false" class="px-4 py-2 bg-gray-500 text-white rounded">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Konfirmasi -->
    <div x-cloak x-show="openDeleteModal" x-transition class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white p-6 rounded-lg w-96 shadow-lg" @click.outside="openDeleteModal = false">
            <h2 class="text-xl font-bold mb-4">Konfirmasi Hapus</h2>
            <p>Apakah Anda yakin ingin menghapus item ini?</p>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" @click="openDeleteModal = false" class="px-4 py-2 bg-gray-500 text-white rounded">Batal</button>
                <form :action="deleteUrl" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
