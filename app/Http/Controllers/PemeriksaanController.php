<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\Checklist;
use App\Models\Kendaraan;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PemeriksaanController extends Controller
{
    public function index()
    {
        return view('pemeriksaans.index');
    }
    public function create(Request $request)
    {
        $jenis = $request->query('jenis', 'utama');
        $checklists = Checklist::jenisKendaraan($jenis)->get();
        $petugas = Petugas::all();
        $kendaraan = Kendaraan::all();
        // dd($kendaraan);
        return view('pemeriksaans.create', compact('checklists', 'petugas', 'jenis', 'kendaraan'));
    }
    public function showpdf()
    {

        $pdfPath = asset('pdfs/pkppk.pdf');


        return view('pdf-view', compact('pdfPath'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'id_petugas' => 'required|exists:petugas,id',
            'id_kendaraan' => 'required|string',
            'dinas' => 'required|in:pagi,malam',
            'checklists' => 'required|array',
            'checklist.*.id_checklist' => 'required|exists:checklists,id',
            'checklist.*.kondisi' => 'required|in:baik,cukup,rusak,tdk ada',
            'checklist.*.keterangan' => 'nullable|string',
        ]);
        $tanggal = \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal)->format('Y-m-d');
        // $hari = \Carbon\Carbon::parse($request->tanggal)->translatedFormat('l');
        // $hari = strtolower($hari);

        // $tanggalFormatted = \Carbon\Carbon::parse($request->tanggal)->format('d/m/Y');

        // $id_hasil = "{$hari}-{$tanggalFormatted}-{$request->dinas}";
        // Ambil nama hari dalam bahasa Indonesia
        $hari = \Carbon\Carbon::parse($tanggal)->translatedFormat('l');
        $hari = strtolower($hari); // Ubah huruf kecil semua

        // Format tanggal menjadi "dmY" (contoh: 26042024)
        $tanggalFormatted = \Carbon\Carbon::parse($tanggal)->format('dmY');

        // Gabungkan nama hari, tanggal, dan dinas tanpa pemisah
        $id_hasil = "{$hari}{$tanggalFormatted}{$request->dinas}";



        $exists = Pemeriksaan::where('tanggal', $tanggal)
            ->where('id_kendaraan', $request->id_kendaraan)
            ->where('dinas', $request->dinas)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors([
                'dinas' => 'Pemeriksaan untuk dinas ini sudah dilakukan pada tanggal dan kendaraan ini.'
            ])->withInput();
        }

        foreach ($request->checklists as $item) {
            Pemeriksaan::create([
                'id_petugas' => $request->id_petugas,
                'dinas' => $request->dinas,
                'id_hasil' => $id_hasil,
                'id_checklist' => $item['id_checklist'],
                'id_kendaraan' => $request->id_kendaraan,
                'tanggal' => $tanggal,
                'kondisi' => $item['kondisi'],
                'keterangan' => $item['keterangan'] ?? null,
            ]);
        }

        // return redirect()->route('pemeriksaan.create', ['jenis' => $request->jenis_kendaraan])
        //     ->with('success', 'Pemeriksaan berhasil disimpan!');
        return redirect()->route('pemeriksaan.cetak', ['id_hasil' => $id_hasil]);
    }
    public function cetak($id_hasil)
    {


        $pemeriksaan = Pemeriksaan::where('id_hasil', $id_hasil)
            ->with('checklist', 'petugas', 'kendaraan')
            ->get();

        if ($pemeriksaan->isEmpty()) {
            return redirect()->back()->withErrors(['error' => 'Data pemeriksaan tidak ditemukan.']);
        }

        // Grupkan hasil berdasarkan kategori
        $sebelum = $pemeriksaan->where('checklist.kategori', 'sebelum');
        $setelah = $pemeriksaan->where('checklist.kategori', 'setelah');
        $testJalan = $pemeriksaan->where('checklist.kategori', 'test_jalan');
        $testPompa = $pemeriksaan->where('checklist.kategori', 'test_pompa');

        // Ambil informasi utama (tanggal, dinas, kendaraan, petugas)
        $info = $pemeriksaan->first();

        return view('pemeriksaans.cetak', compact('info', 'sebelum', 'setelah', 'testJalan', 'testPompa'));
    }

    //     public function recap()
    // {
    //     // Mengambil semua data pemeriksaan, termasuk relasi ke petugas, kendaraan, dan checklist
    //     $hasil = Pemeriksaan::select('id_hasil', 'tanggal', 'dinas', 'id_petugas', 'id_kendaraan')
    //         ->with(['petugas', 'kendaraan'])
    //         ->groupBy('id_hasil', 'tanggal', 'dinas', 'id_petugas', 'id_kendaraan') // Untuk mencegah duplikasi ID hasil
    //         ->orderBy('tanggal', 'desc')
    //         ->get();

    //     return view('pemeriksaans.recap', compact('hasil'));
    // }
    public function recap(Request $request)
    {
        $search = $request->get('search');
        $dinas = $request->get('dinas');
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');
        $sortBy = $request->get('sortBy');
        $sort = $request->get('sort', 'asc');

        if (!empty($startDate)) {
            $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d');
        }

        if (!empty($endDate)) {
            $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d');
        }

        $query = Pemeriksaan::query();


        // if (Auth::user()->role == 'petugas') {
        //     $query->whereHas(
        //         'user',
        //         function ($userQuery) {
        //             $userQuery->where('user_id', Auth::user()->id);
        //         }
        //     );
        // }

        $query->with(['petugas', 'kendaraan']);

        if (!empty($dinas)) {
            $query->WhereIn('dinas', $dinas);
        }

        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query
                    ->whereHas('kendaraan', function ($query) use ($search) {
                        $query->where('nama_kendaraan', 'like', "%{$search}%");
                    })
                    ->orWhereHas('petugas', function ($query) use ($search) {
                        $query->where('nama_petugas', 'like', "%{$search}%");
                    });
            });
        }

        if (!empty($sortBy) && is_string($sortBy) && in_array($sort, ['asc', 'desc'])) {
            if ($sortBy == 'tanggal') {
                $query->orderByRaw('CAST(' . $sortBy . ' AS UNSIGNED) ' . $sort);
            } else {
                $query->orderBy($sortBy, $sort);
            }
        }


        $pemeriksaans = $query->paginate(10)->appends(request()->query());

        $groupedDinas = Pemeriksaan::all()->groupBy('dinas')->map(function ($items, $dinas) {
            return (object) [
                'dinas' => $dinas,
                'total' => $items->count()
            ];
        });

        $rowCallback = function ($value, $field) {
            if ($field == 'dinas') {
                return ucfirst($value);
            }
            if ($field == 'id_kendaraan') {
                $id_kendaraan = $value;
                $kendaraan = Kendaraan::find($id_kendaraan);
                return $kendaraan->nama_kendaraan;
            }
            if ($field == 'id_petugas') {
                $id_petugas = $value;
                $petugas = Petugas::find($id_petugas);
                return $petugas->nama_petugas;
            }
            if ($field == 'id_hasil') {
                preg_match('/\d+/', $value, $matches);
                $tanggalRaw = $matches[0];

                $dinas = substr($value, strpos($value, $tanggalRaw) + strlen($tanggalRaw));

                $tanggal = \Carbon\Carbon::createFromFormat('dmY', $tanggalRaw);
                $hari = strtolower($tanggal->locale('id')->isoFormat('dddd'));

                return
                    "{$hari}-{$tanggal->format('d-m-Y')}-{$dinas}";
            }
            if ($field == 'tanggal') {
                $tanggal = \Carbon\Carbon::parse($value);
                return $tanggal->format('d-m-Y');
            }
            return $value;
        };

        $filterCount = count(array_filter(array_merge(
            $dinas ?? []
        ), function ($value) {
            return $value !== null;
        }));

        $sortable = (object) [
            'tanggal' => (object) ['label' => 'Tanggal', 'field' => 'tanggal'],
        ];

        return view('pemeriksaans.recap', compact('pemeriksaans', 'groupedDinas', 'filterCount', 'sortable', 'rowCallback'));
    }



    public function arsip($id_hasil)
    {
        $pemeriksaans = Pemeriksaan::where('id_hasil', $id_hasil)
            ->with('checklist', 'petugas', 'kendaraan')
            ->get();

        if ($pemeriksaans->isEmpty()) {
            return redirect()->back()->withErrors(['error' => 'Data arsip tidak ditemukan.']);
        }

        // Ambil informasi utama (tanggal, dinas, kendaraan, petugas)
        $info = $pemeriksaans->first();

        return view('pemeriksaans.arsip', compact('info', 'pemeriksaans'));
    }

    public function fetch(Request $request)
    {
        $query = Pemeriksaan::select('id_hasil', 'tanggal', 'dinas', 'id_petugas', 'id_kendaraan')
            ->with(['petugas', 'kendaraan']);

        // Filter pencarian
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('petugas', function ($q) use ($search) {
                $q->where('nama_petugas', 'LIKE', "%{$search}%");
            })->orWhereHas('kendaraan', function ($q) use ($search) {
                $q->where('nama_kendaraan', 'LIKE', "%{$search}%");
            })->orWhere('tanggal', 'LIKE', "%{$search}%");
        }

        // Urutkan berdasarkan tanggal
        if ($request->has('order')) {
            $query->orderBy('tanggal', $request->order);
        }

        // Kelompokkan berdasarkan dinas
        if ($request->has('group')) {
            $query->orderBy('dinas');
        }

        $hasil = $query->get();


        $hasilFormatted = $hasil->map(function ($item) {
            $tanggal = \Carbon\Carbon::parse($item->tanggal);
            $hari = strtolower($tanggal->translatedFormat('l')); // Contoh: 'sabtu'

            $tanggalFormatted = $tanggal->format('dmY');
            $idHasilBaru = "{$hari}{$tanggalFormatted}{$item->dinas}";
            $idHasilFormatted = "{$hari}-{$tanggal->format('d-m-Y')}-{$item->dinas}";

            return [
                'hasil' => $idHasilBaru,
                'id_hasil' => $idHasilFormatted,
                'tanggal' => $item->tanggal,
                'dinas' => ucfirst($item->dinas),
                'petugas' => $item->petugas->nama_petugas ?? '-',
                'kendaraan' => $item->kendaraan->nama_kendaraan ?? '-',
            ];
        });

        $html = view('pemeriksaans.partials.rekapBody', compact('hasilFormatted'))->render();

        return response()->json(['html' => $html]);
    }
}
