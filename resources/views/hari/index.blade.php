@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Hari</h1>
    <a href="{{ route('hari.create') }}" class="btn btn-primary mb-3">Tambah Hari</a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Hari</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($haris as $hari)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $hari->nama }}</td>
                    <td>
                        {{-- <a href="{{ route('hari.show', $hari->id) }}" class="btn btn-info btn-sm">Detail</a> --}}
                        <a href="{{ route('hari.edit', $hari->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('hari.destroy', $hari->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus hari ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
