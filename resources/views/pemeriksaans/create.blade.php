

@extends('pemeriksaans.template.template')

@section('content')
<div class="container">
    <h1>Form Pemeriksaan - {{ ucfirst($jenis) }}</h1>

    <form action="{{ route('pemeriksaan.store') }}" method="POST">
        @csrf
        <input type="hidden" name="jenis_kendaraan" value="{{ $jenis }}">
<div class="mb-3">
    <label class="form-label">Dinas</label>
    <div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="dinas" default checked id="dinasPagi" value="pagi" required>
            <label class="form-check-label" for="dinasPagi">Pagi</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="dinas" id="dinasSore" value="malam" required>
            <label class="form-check-label" for="dinasSore">Malam</label>
        </div>
    </div>
</div>
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="id_petugas" class="form-label">Petugas</label>
            <select name="id_petugas" class="form-select" required>
                <option value="">Pilih Petugas</option>
                @foreach ($petugas as $p)
                    <option value="{{ $p->id }}">{{ $p->nama_petugas }} ({{ $p->regu }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="id_kendaraan" class="form-label">Kendaraan</label>
            <select name="id_kendaraan" class="form-select" required>
                <option value="">Pilih Kendaraan</option>
                @foreach ($kendaraan as $p)
                    <option value="{{ $p->id }}">{{ $p->nama_kendaraan }}</option>
                @endforeach
            </select>
        </div>

        {{-- <div class="mb-3">
            <label for="kendaraan" class="form-label">Kendaraan</label>
            <input type="text" name="kendaraan" class="form-control" required>
        </div> --}}

        <div class="mb-3">
            <label class="form-label">Checklist Pemeriksaan</label>
            @if ($checklists->isEmpty())
                <p>Tidak ada item checklist untuk jenis kendaraan ini.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Kondisi</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($checklists as $checklist)
                        <tr>
                            <td>{{ $checklist->nama_item }}</td>
                            <td>
                                <select name="checklists[{{ $loop->index }}][kondisi]" class="form-select" required>
                                    <option value="">Pilih Kondisi</option>
                                    <option value="baik">Baik</option>
                                    <option value="cukup">Cukup</option>
                                    <option value="rusak">Rusak</option>
                                    <option value="tdk ada">Tidak Ada</option>
                                </select>
                                <input type="hidden" name="checklists[{{ $loop->index }}][id_checklist]" value="{{ $checklist->id }}">
                            </td>
                            <td>
                                <input type="text" name="checklists[{{ $loop->index }}][keterangan]" class="form-control">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
