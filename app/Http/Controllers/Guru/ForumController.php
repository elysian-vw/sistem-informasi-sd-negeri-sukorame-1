<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\ForumKomentar;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index()
    {
        $forums = Forum::with(['user', 'komentar'])->latest()->paginate(10);
        return view('guru.forum.index', compact('forums'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required',
        ]);

        Forum::create([
            'user_id' => auth()->id(),
            'judul' => $request->judul,
            'konten' => $request->konten,
        ]);

        return redirect()->back()->with('success', 'Topik forum berhasil dibuat.');
    }

    public function show(Forum $forum)
    {
        $forum->load(['user', 'komentar.user']);
        return view('guru.forum.show', compact('forum'));
    }

    public function destroy(Forum $forum)
    {
        if ($forum->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $forum->delete();
        return redirect()->route('guru.forum.index')->with('success', 'Topik forum berhasil dihapus.');
    }

    public function komentar(Request $request, Forum $forum)
    {
        $request->validate([
            'konten' => 'required',
        ]);

        ForumKomentar::create([
            'forum_id' => $forum->id,
            'user_id' => auth()->id(),
            'konten' => $request->konten,
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    public function hapusKomentar(ForumKomentar $komentar)
    {
        if ($komentar->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $komentar->delete();
        return redirect()->back()->with('success', 'Komentar berhasil dihapus.');
    }

    public function togglePin(Forum $forum)
    {
        $forum->update(['is_pinned' => !$forum->is_pinned]);
        return redirect()->back()->with('success', 'Status pin berhasil diubah.');
    }
}
