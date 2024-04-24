<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Foto;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Controller
{
    public function guest()
    {
        $fotos = Foto::with(['album', 'likes', 'user', 'comments'])->get();
        // Kirim variabel album dan liked ke halaman tampilan
        return view('welcome', ['fotos' => $fotos]);
    }
    public function index()
    {
        $fotos = Foto::with(['album', 'likes', 'user', 'comments'])->get();

        // Dapatkan ID pengguna yang saat ini login
        $userId = auth()->id();

        // Loop melalui setiap foto dan tambahkan properti liked ke setiap foto
        foreach ($fotos as $foto) {
            // Cek apakah pengguna sudah menyukai foto tersebut
            $liked = Like::where('user_id', $userId)
                ->where('foto_id', $foto->id)
                ->exists();

            // Tambahkan properti liked ke objek foto
            $foto->liked = $liked;
        }

        // Kirim variabel album dan liked ke halaman tampilan
        return view('dashboard', ['fotos' => $fotos]);
    }

    public function like(Request $request)
    {
        // Ambil user ID dari pengguna yang sedang login
        $userId = auth()->user()->id;

        // Terima data like yang dikirim melalui permintaan Ajax
        $fotoId = $request->input('foto_id');

        // Cari data like yang sesuai dengan user_id dan foto_id
        $existingLike = Like::where('foto_id', $fotoId)
            ->where('user_id', $userId)
            ->first();

        if ($existingLike) {
            // Jika data like sudah ada, hapus data tersebut
            $existingLike->delete();
            $message = 'Like removed successfully.';

            // dd($existingLike);
        } else {
            // Jika data like belum ada, buatkan data baru
            $like = new Like();
            $like->foto_id = $fotoId;
            $like->user_id = $userId;
            $like->save();
            $message = 'Like added successfully.';
        }

        // Berikan respons kembali ke klien
        return response()->json(['success' => true, 'message' => $message]);
    }

    public function commentStore(Request $request)
    {
        // dd($request);
        // Dapatkan ID foto dari permintaan
        $fotoId = $request->foto_id;
        $description = $request->comment;
        // Dapatkan ID pengguna yang saat ini login
        $userId = auth()->id();

        // Cek apakah pengguna sudah menyukai foto tersebut 
        $comment = new comment();
        $comment->foto_id = $fotoId;
        $comment->user_id = $userId;
        $comment->description = $description;
        $comment->save();

        if ($comment->wasRecentlyCreated) {
            return redirect()->back()->with(['comment_sent' => true, 'modal_id' => $fotoId]);
        } else {
            // Jika komentar gagal disimpan, tampilkan pesan kesalahan atau lakukan tindakan yang sesuai
            // Contoh: return redirect()->back()->with('error_message', 'Gagal menyimpan komentar');
        }
    }
}
