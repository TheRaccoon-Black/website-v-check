<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function index()
    {
        $kendaraans = Kendaraan::all();
        return view('kendaraans.index', compact('kendaraans'));
    }

    public function create()
    {
        return view('kendaraans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_polisi' => 'required|unique:kendaraans,no_polisi',
            'nama_kendaraan' => 'required',
            'merk' => 'nullable',
            'tahun' => 'nullable|numeric',
        ]);

        Kendaraan::create($request->all());

        return redirect()->route('kendaraans.index')->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        return view('kendaraans.edit', compact('kendaraan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'no_polisi' => 'required|unique:kendaraans,no_polisi,' . $id,
            'nama_kendaraan' => 'required',
            'merk' => 'nullable',
            'tahun' => 'nullable|numeric',
        ]);

        $kendaraan = Kendaraan::findOrFail($id);
        $kendaraan->update($request->all());

        return redirect()->route('kendaraans.index')->with('success', 'Kendaraan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $kendaraan->delete();

        return redirect()->route('kendaraans.index')->with('success', 'Kendaraan berhasil dihapus.');
    }
}
