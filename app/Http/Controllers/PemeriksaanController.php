<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\Checklist;
use App\Models\Kendaraan;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;

class PemeriksaanController extends Controller
{
    public function index(){
        return view('pemeriksaans.index');
    }
    public function create(Request $request)
    {
        $jenis = $request->query('jenis', 'utama');
        $checklists = Checklist::jenisKendaraan($jenis)->get();
        $petugas = Petugas::all();
        $kendaraan = Kendaraan::all();
        // dd($kendaraan);
        return view('pemeriksaans.create', compact('checklists', 'petugas', 'jenis','kendaraan'));
    }

    public function store(Request $request)
{
    $request->validate([
        'tanggal' => 'required|date',
        'id_petugas' => 'required|exists:petugas,id',
        'id_kendaraan' => 'required|string',
        'dinas' => 'required|in:pagi,malam', // Pastikan hanya "pagi" atau "malam"
        'checklists' => 'required|array',
        'checklists.*.id_checklist' => 'required|exists:checklists,id',
        'checklists.*.kondisi' => 'required|in:baik,cukup,rusak,tdk ada',
        'checklists.*.keterangan' => 'nullable|string',
    ]);

    // Format hari ke dalam bahasa Indonesia
    $hari = \Carbon\Carbon::parse($request->tanggal)->translatedFormat('l');
    $hari = strtolower($hari);

    // Format tanggal menjadi d/m/Y
    $tanggalFormatted = \Carbon\Carbon::parse($request->tanggal)->format('d/m/Y');

    // Buat id_hasil
    $id_hasil = "{$hari}-{$tanggalFormatted}-{$request->dinas}";

    // Validasi unik: Pastikan dinas pagi/malam hanya sekali per hari
    $exists = Pemeriksaan::where('tanggal', $request->tanggal)
        ->where('id_kendaraan', $request->id_kendaraan)
        ->where('dinas', $request->dinas)
        ->exists();

    if ($exists) {
        return redirect()->back()->withErrors([
            'dinas' => 'Pemeriksaan untuk dinas ini sudah dilakukan pada tanggal dan kendaraan ini.'
        ])->withInput();
    }

    // Simpan data pemeriksaan
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

    return redirect()->route('pemeriksaan.create', ['jenis' => $request->jenis_kendaraan])
        ->with('success', 'Pemeriksaan berhasil disimpan!');
}


}
