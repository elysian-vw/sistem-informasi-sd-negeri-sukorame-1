<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keunggulan;
use Illuminate\Http\Request;

class KeunggulanController extends Controller
{
    public function index()
    {
        $keunggulan = Keunggulan::orderBy('urutan')->get();
        return view('admin.keunggulan.index', compact('keunggulan'));
    }

    public function create()
    {
        return view('admin.keunggulan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'icon'        => 'required|string|max:100',
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string|max:500',
            'color'       => 'required|in:blue,indigo,purple,amber,pink,teal,green,red',
            'urutan'      => 'nullable|integer|min:0',
            'is_aktif'    => 'nullable|boolean',
        ]);

        Keunggulan::create([
            'icon'        => $request->icon,
            'title'       => $request->title,
            'description' => $request->description,
            'color'       => $request->color,
            'urutan'      => $request->urutan ?? 0,
            'is_aktif'    => $request->boolean('is_aktif', true),
        ]);

        return redirect()->route('admin.keunggulan.index')
            ->with('success', 'Keunggulan berhasil ditambahkan!');
    }

    public function edit(Keunggulan $keunggulan)
    {
        return view('admin.keunggulan.edit', compact('keunggulan'));
    }

    public function update(Request $request, Keunggulan $keunggulan)
    {
        $request->validate([
            'icon'        => 'required|string|max:100',
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string|max:500',
            'color'       => 'required|in:blue,indigo,purple,amber,pink,teal,green,red',
            'urutan'      => 'nullable|integer|min:0',
            'is_aktif'    => 'nullable|boolean',
        ]);

        $keunggulan->update([
            'icon'        => $request->icon,
            'title'       => $request->title,
            'description' => $request->description,
            'color'       => $request->color,
            'urutan'      => $request->urutan ?? 0,
            'is_aktif'    => $request->boolean('is_aktif', true),
        ]);

        return redirect()->route('admin.keunggulan.index')
            ->with('success', 'Keunggulan berhasil diperbarui!');
    }

    public function destroy(Keunggulan $keunggulan)
    {
        $keunggulan->delete();
        return redirect()->route('admin.keunggulan.index')
            ->with('success', 'Keunggulan berhasil dihapus.');
    }
}