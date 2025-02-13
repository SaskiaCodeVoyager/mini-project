<!-- resources/views/jadpik/edit.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Jadpik</h1>
    
    <form action="{{ route('jadpik.update', $jadpik->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label for="nama_siswa" class="form-label">Nama Siswa</label>
            <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" value="{{ $jadpik->nama_siswa }}" required>
        </div>
        
        <div class="mb-3">
            <label for="user_id" class="form-label">Pengguna</label>
            <select class="form-select" id="user_id" name="user_id" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $jadpik->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="hari_id" class="form-label">Hari</label>
            <select class="form-select" id="hari_id" name="hari_id" required>
                @foreach($haris as $hari)
                    <option value="{{ $hari->id }}" {{ $jadpik->hari_id == $hari->id ? 'selected' : '' }}>
                        {{ $hari->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
