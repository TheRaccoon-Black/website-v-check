<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use Illuminate\Http\Request;

class ChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $group = $request->input('group'); // Cek parameter 'group' dari URL
    $checklists = Checklist::all();

    // Grupkan berdasarkan parameter yang diterima
    if ($group == 'kategori') {
        $groupedChecklists = $checklists->groupBy('kategori');
    } elseif ($group == 'jenis_kendaraan') {
        $groupedChecklists = $checklists->groupBy('jenis_kendaraan');
    } else {
        // Jika tidak ada pengelompokan, tampilkan semua data dalam satu grup default
        $groupedChecklists = collect(['Semua Checklist' => $checklists]);
    }

    return view('checklists.index', compact('groupedChecklists'));
}


    public function create()
    {
        return view('checklists.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_item' => 'required|string|max:255',
            'kategori' => 'required|in:sebelum,setelah,test_jalan,test_pompa',
            'jenis_kendaraan' => 'required|in:utama,pendukung',
        ]);

        Checklist::create($request->all());
        return redirect()->route('checklists.index')->with('success', 'Checklist berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $checklist = Checklist::findOrFail($id);
        return view('checklists.edit', compact('checklist'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_item' => 'required|string|max:255',
            'kategori' => 'required|in:sebelum,setelah,test_jalan,test_pompa',
            'jenis_kendaraan' => 'required|in:utama,pendukung',
        ]);

        $checklist = Checklist::findOrFail($id);
        $checklist->update($request->all());

        return redirect()->route('checklists.index')->with('success', 'Checklist berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $checklist = Checklist::findOrFail($id);
        $checklist->delete();

        return redirect()->route('checklists.index')->with('success', 'Checklist berhasil dihapus.');
    }
}
