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
        $search = $request->get('search');
        $kategori = $request->get('kategori');
        $jenis_kendaraan = $request->get('jenis_kendaraan');

        $query = Checklist::query();

        if (!empty($kategori)) {
            $query->WhereIn('kategori', $kategori);
        }

        if (!empty($jenis_kendaraan)) {
            $query->WhereIn('jenis_kendaraan', $jenis_kendaraan);
        }

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('nama_item', 'like', "%{$search}%");
            });
        }

        $checklists = $query->paginate(10)->appends(request()->query());

        $groupedKategori = Checklist::all()->groupBy('kategori')->map(function ($items, $kategori) {
            return (object) [
                'kategori' => $kategori,
                'total' => $items->count()
            ];
        });

        $groupedJenisKendaraan =
            Checklist::all()->groupBy('jenis_kendaraan')->map(function ($items, $jenis_kendaraan) {
                return (object) [
                    'jenis_kendaraan' => $jenis_kendaraan,
                    'total' => $items->count()
                ];
            });

        $rowCallback = function ($value, $field) {
            if ($field === 'kategori' && $value === 'test_pompa') {
                return 'Test Pompa';
            }
            if ($field === 'kategori' && $value === 'test_jalan') {
                return 'Test Jalan';
            }
            if ($field === 'kategori' && $value === 'sebelum') {
                return 'Sebelum';
            }
            if ($field === 'kategori' && $value === 'setelah') {
                return 'Setelah';
            }
            if ($field === 'jenis_kendaraan' && $value === 'utama') {
                return 'Kendaraan Utama';
            }
            if ($field === 'jenis_kendaraan' && $value === 'pendukung') {
                return 'Kendaraan Pendukung';
            }
            return $value;
        };

        return view('checklists.index', compact('checklists', 'groupedKategori', 'groupedJenisKendaraan', 'rowCallback'));
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
        return redirect()->route('checklist.index')->with('success', 'Checklist berhasil ditambahkan.');
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

        return redirect()->route('checklist.index')->with('success', 'Checklist berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $checklist = Checklist::findOrFail($id);
        $checklist->delete();

        return redirect()->route('checklist.index')->with('success', 'Checklist berhasil dihapus.');
    }
}
