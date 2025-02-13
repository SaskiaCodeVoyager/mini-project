<!-- resources/views/jadpik/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Jadpik Baru</h1>
    
    <form action="{{ route('jadpik.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nama_siswa" class="form-label">Nama Siswa</label>
            <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" required>
        </div>
        
        <div class="mb-3">
            <label for="user_id" class="form-label">Pengguna</label>
            <select class="form-select" id="user_id" name="user_id" required>
                <option value="">Pilih Pengguna</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->id_user}}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="hari_id" class="form-label">Hari</label>
            <select class="form-select" id="hari_id" name="hari_id" required>
                <option value="">Pilih Hari</option>
                @foreach($haris as $hari)
                    <option value="{{ $hari->id }}">{{ $hari->nama }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
