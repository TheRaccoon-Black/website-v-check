<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $search = $request->get('search');
    $regu = $request->get('regu');
    $users = User::all();

    $query = Petugas::query();

    if (!empty($search)) {
        $query->where(function ($query) use ($search) {
            $query->whereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%{$search}%");
            })->orWhere('regu', 'like', "%{$search}%")
              ->orWhere('petugas_id', 'like', "%{$search}%");
        });
    }

    if (!empty($regu) && is_array($regu)) {
        $query->whereIn('regu', $regu);
    }

    $petugas = $query->with('user')->paginate(10)->appends(request()->query());

    // $petugas = $query->paginate(10)->appends(request()->query());

    $grouped = Petugas::all()->groupBy('regu')->map(function ($items, $regu) {
        return (object) [
            'regu' => $regu,
            'total' => $items->count(),
        ];
    });

    $filterCount = count(array_filter($regu ?? [], function ($value) {
        return $value !== null;
    }));

    return view('petugas.index', compact('petugas','users', 'grouped', 'filterCount'));
}

    public function create()
    {
        return view('petugas.create');
    }

    public function store(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'regu' => 'required|string|max:255',
            'petugas_id' => 'required|string|max:255|unique:petugas',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('error', 'Petugas gagal ditambahkan.');
        }

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
            'user_id' => 'required|exists:users,id',
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
