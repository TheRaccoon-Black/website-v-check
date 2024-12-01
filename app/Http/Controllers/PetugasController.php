<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\Request;

class PetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $petugas = Petugas::paginate(10);
        $grouped = $petugas->groupBy('regu')->map(function ($items, $regu) {
            return (object) [
                'regu' => $regu,
                'total' => $items->count()
            ];
        });

        return view('petugas.index', compact('petugas', 'grouped'));
    }

    public function create()
    {
        return view('petugas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_petugas' => 'required|string|max:255',
            'regu' => 'required|string|max:255',
            'petugas_id' => 'required|string|max:255|unique:petugas',
        ]);

        Petugas::create($request->all());

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $petugas = Petugas::findOrFail($id);
        return view('petugas.edit', compact('petugas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_petugas' => 'required|string|max:255',
            'regu' => 'required|string|max:255',
            'petugas_id' => 'required|string|max:255|unique:petugas,petugas_id,' . $id,
        ]);

        $petugas = Petugas::findOrFail($id);
        $petugas->update($request->all());

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);
        $petugas->delete();

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil dihapus.');
    }

    /**
     * Show the form for creating a new resource.
     */
}
