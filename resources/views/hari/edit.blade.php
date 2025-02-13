@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Hari</h1>

    <form action="{{ route('hari.update', $hari->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nama">Nama Hari</label>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $hari->nama) }}" required>
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

        <button type="submit" class="btn btn-primary mt-3">Perbarui</button>
    </form>
</div>
@endsection
