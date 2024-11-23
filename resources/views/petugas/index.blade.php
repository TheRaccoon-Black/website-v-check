@extends('petugas.template.template')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Daftar Petugas</h3>
        <a href="{{ route('petugas.create') }}" class="btn btn-primary">Tambah Petugas</a>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="example2" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Petugas</th>
                    <th>Regu</th>
                    <th>ID Petugas</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($petugas as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->nama_petugas }}</td>
                    <td>{{ $p->regu }}</td>
                    <td>{{ $p->petugas_id }}</td>
                    <td>
                        <a href="{{ route('petugas.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('petugas.destroy', $p->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus petugas ini?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>No</th>
                    <th>Nama Petugas</th>
                    <th>Regu</th>
                    <th>ID Petugas</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- /.card-body -->
</div>
@endsection
