@extends('petugas.template.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Daftar Kendaraan</h3>
            <a href="{{ route('kendaraan.create') }}" class="btn btn-primary float-right">Tambah Kendaraan</a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Polisi</th>
                        <th>Nama Kendaraan</th>
                        <th>Merk</th>
                        <th>Tahun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kendaraans as $key => $kendaraan)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $kendaraan->no_polisi }}</td>
                            <td>{{ $kendaraan->nama_kendaraan }}</td>
                            <td>{{ $kendaraan->merk }}</td>
                            <td>{{ $kendaraan->tahun }}</td>
                            <td>
                                <a href="{{ route('kendaraan.edit', $kendaraan->id) }}"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('kendaraan.destroy', $kendaraan->id) }}" method="POST"
                                    style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
