@extends('pemeriksaans.template.template')

@section('content')
<div class="container">
    <h1>Rekap Pemeriksaan</h1>

    <!-- Tombol untuk pengurutan dan pengelompokan -->
    <div class="mb-3">
        <button id="sortAsc" class="btn btn-primary btn-sm">Urutkan Tanggal (Asc)</button>
        <button id="sortDesc" class="btn btn-primary btn-sm">Urutkan Tanggal (Desc)</button>
        <button id="groupDinas" class="btn btn-primary btn-sm">Kelompokkan Berdasarkan Dinas</button>
    </div>

    <!-- Pencarian -->
    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Cari berdasarkan petugas, kendaraan, atau tanggal">
    </div>

    <!-- Tabel -->
    <table class="table table-bordered" id="rekapTable">
        <thead>
            <tr>
                <th>Keterangan Hasil</th>
                <th>Tanggal</th>
                <th>Dinas</th>
                <th>Petugas</th>
                <th>Kendaraan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="rekapBody">
            @php
                $hariIndonesia = [
                    'monday' => 'senin',
                    'tuesday' => 'selasa',
                    'wednesday' => 'rabu',
                    'thursday' => 'kamis',
                    'friday' => 'jumat',
                    'saturday' => 'sabtu',
                    'sunday' => 'minggu',
                ];
            @endphp
            @forelse ($hasilFormatted as $item)
                <tr>
                    <td>
                        @php
                            $idParts = explode('-', $item['id_hasil']);
                            $hariEnglish = $idParts[0] ?? '';
                            $hariIndo = $hariIndonesia[$hariEnglish] ?? $hariEnglish;
                            $idHasilFinal = str_replace($hariEnglish, $hariIndo, $item['id_hasil']);
                        @endphp
                        {{ $idHasilFinal }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}</td>
                    <td>{{ $item['dinas'] }}</td>
                    <td>{{ $item['petugas'] }}</td>
                    <td>{{ $item['kendaraan'] }}</td>
                    <td>
                        <a href="{{ route('pemeriksaan.cetak', $item['hasil']) }}" class="btn btn-primary btn-sm">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // Sorting Ascending
        $('#sortAsc').on('click', function () {
            fetchData('asc');
        });

        // Sorting Descending
        $('#sortDesc').on('click', function () {
            fetchData('desc');
        });

        // Grouping by Dinas
        $('#groupDinas').on('click', function () {
            fetchData(null, 'group');
        });

        // Searching with AJAX
        $('#searchInput').on('keyup', function () {
            const query = $(this).val();
            fetchData(null, null, query);
        });

        function fetchData(order = null, group = null, search = null) {
    $.ajax({
        url: "{{ route('pemeriksaan.fetch') }}",
        type: 'GET',
        data: { order, group, search },
        success: function (response) {
            console.log(response); // Tambahkan log ini untuk debugging
            $('#rekapBody').html(response.html);
        },
        error: function (xhr) {
            console.error(xhr.responseText); // Tambahkan log untuk melihat kesalahan
            alert('Terjadi kesalahan saat memuat data.');
        }
    });
}

    });
</script>
@endpush
