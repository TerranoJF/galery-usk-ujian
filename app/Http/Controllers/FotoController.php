<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Foto;
use App\Models\Album;
use Illuminate\Support\Facades\Auth;

class FotoController extends Controller
{
    public function index()
    {
        // Mendapatkan user_id dari pengguna yang saat ini diotentikasi
        $user_id = Auth::id();
        // Mengambil data album berdasarkan user_id
        $albums = Album::where('user_id', $user_id)->get();
        $fotos = Foto::where('user_id', $user_id)->with('album')->get();
        return view('foto', compact('albums', 'fotos'));
    }
    // public function index(Request $request)
    // {
    //     // Ambil ID pengguna yang sedang login
    //     $userId = Auth::id();
    //     // Ambil semua album yang berelasi dengan user tertentu
    //     $fotos = Foto::where('users_id', $userId)->with('album')->get();
    //     $userAlbums = Album::where('user_id', $userId)->get();
    //     // dd($fotos);

    //     // Kirim variabel album ke halaman tampilan
    //     return view('foto', ['fotos' => $fotos,  'albums' => $userAlbums]);
    // }
 
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name_foto' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // tambahkan validasi untuk file gambar
            'album_id' => 'required|exists:albums,id' // tambahkan validasi untuk album_id
        ]);

        // Mendapatkan user_id dari pengguna yang saat ini diotentikasi
        $user_id = Auth::id();

        // Simpan gambar yang diunggah ke penyimpanan yang diinginkan
        // (misalnya: storage/app/public/foto)
        $imagePath = $request->file('image')->store('public/foto');

        // Dapatkan nama file dari path gambar yang disimpan
        $imageName = basename($imagePath);

        // Buat path relatif dengan menambahkan prefix 'foto/'
        $relativePath = 'foto/' . $imageName;

        // Membuat record baru dalam tabel foto
        Foto::create([
            'name' => $request->name_foto,
            'description' => $request->description,
            'file_location' => $relativePath,
            'album_id' => $request->album_id,
            'user_id' => $user_id
        ]);

        // Redirect dengan pesan sukses jika berhasil
        return redirect()->route('fotos')->with('success', 'Foto berhasil disimpan.');
    }

    public function update(Request $request, Foto $foto)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($foto->user_id !== Auth::id()) {
            return redirect()->route('fotos')->with('error', 'You are not authorized to update this foto.');
        }

        $foto->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('fotos')->with('success', 'Foto updated successfully.');
    }


    public function destroy(Foto $foto)
    {
        $foto->delete();

        return redirect()->route('fotos')->with('success', 'Foto deleted successfully.');
    }
}
