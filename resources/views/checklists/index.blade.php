@extends('petugas.template.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Checklist</h3>
        <a href="{{ route('checklists.create') }}" class="btn btn-primary float-right">Tambah Checklist</a>
    </div>
    <div class="card-body">
        <!-- Tombol untuk pengelompokan -->
        <div class="mb-3">
            <a href="{{ route('checklists.index', ['group' => 'kategori']) }}" class="btn btn-info">Kelompokkan Berdasarkan Kategori</a>
            <a href="{{ route('checklists.index', ['group' => 'jenis_kendaraan']) }}" class="btn btn-secondary">Kelompokkan Berdasarkan Jenis Kendaraan</a>
        </div>
        <!-- Tabel Checklist -->
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Item</th>
                    <th>Kategori</th>
                    <th>Jenis Kendaraan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($groupedChecklists as $groupKey => $groupedItems)
                    <tr>
                        <td colspan="5" class="table-secondary">
                            <strong>{{ ucfirst($groupKey) }}</strong>
                        </td>
                    </tr>
                    @foreach ($groupedItems as $key => $checklist)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $checklist->nama_item }}</td>
                            <td>{{ ['sebelum' => 'Sebelum', 'setelah' => 'Setelah', 'test_jalan' => 'Test Jalan', 'test_pompa' => 'Test Pompa'][$checklist->kategori] }}</td>
                            <td>{{ ucfirst($checklist->jenis_kendaraan) }}</td>
                            <td>
                                <a href="{{ route('checklists.edit', $checklist->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('checklists.destroy', $checklist->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Data checklist belum tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
