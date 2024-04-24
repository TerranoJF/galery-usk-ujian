<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use Illuminate\Support\Facades\Auth;

class AlbumController extends Controller
{
    public function index()
    {
        // Mendapatkan user_id dari pengguna yang saat ini diotentikasi
        $user_id = Auth::id();
        $albums = Album::where('user_id', $user_id)->get();
        return view('album', compact('albums'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name_album' => 'required|string|max:255',
            'description' => 'required|string',
            // tambahkan validasi lainnya sesuai kebutuhan
        ]);

        // Mendapatkan user_id dari pengguna yang saat ini diotentikasi
        $user_id = Auth::id();

        // Membuat album dengan user_id dari pengguna yang saat ini diotentikasi
        $album = new Album([
            'name' => $request->name_album,
            'description' => $request->description,
            'user_id' => $user_id,
        ]);

        // dd($album);
        $album->save();
        return redirect()->route('albums')->with('success', 'Album created successfully.');
    }

    public function update(Request $request, Album $album)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($album->user_id !== Auth::id()) {
            return redirect()->route('albums.index')->with('error', 'You are not authorized to update this album.');
        }

        $album->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('albums')->with('success', 'Album updated successfully.');
    }

    public function destroy(Album $album)
    {
        $album->delete();

        return redirect()->route('albums')->with('success', 'Album deleted successfully.');
    }
}
