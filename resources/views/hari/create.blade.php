@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Hari Baru</h1>

    <form action="{{ route('hari.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nama">Nama Hari</label>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" required>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
    </form>
</div>
@endsection
