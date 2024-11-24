<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\Checklist;
use App\Models\Kendaraan;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'id_petugas' => 'required|exists:petugas,id',
            'id_kendaraan' => 'required|string',
            'dinas' => 'required|in:pagi,malam',
            'checklists' => 'required|array',
            'checklists.*.id_checklist' => 'required|exists:checklists,id',
            'checklists.*.kondisi' => 'required|in:baik,cukup,rusak,tdk ada',
            'checklists.*.keterangan' => 'nullable|string',
        ]);


        // $hari = \Carbon\Carbon::parse($request->tanggal)->translatedFormat('l');
        // $hari = strtolower($hari);

        // $tanggalFormatted = \Carbon\Carbon::parse($request->tanggal)->format('d/m/Y');

        // $id_hasil = "{$hari}-{$tanggalFormatted}-{$request->dinas}";
        // Ambil nama hari dalam bahasa Indonesia
        $hari = \Carbon\Carbon::parse($request->tanggal)->translatedFormat('l');
        $hari = strtolower($hari); // Ubah huruf kecil semua

        // Format tanggal menjadi "dmY" (contoh: 26042024)
        $tanggalFormatted = \Carbon\Carbon::parse($request->tanggal)->format('dmY');

        // Gabungkan nama hari, tanggal, dan dinas tanpa pemisah
        $id_hasil = "{$hari}{$tanggalFormatted}{$request->dinas}";



        $exists = Pemeriksaan::where('tanggal', $request->tanggal)
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
                'tanggal' => $request->tanggal,
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


    public function recap()
    {
        $hasil = Pemeriksaan::all();
        return view('pemeriksaans.recap', compact('hasil'));
    }
}
