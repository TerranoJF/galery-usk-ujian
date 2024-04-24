<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Foto extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', // Tambahkan 'name' ke dalam properti fillable
        'description',
        'file_location',
        'album_id',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class, 'album_id', 'id');
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes', 'foto_id', 'user_id');
    }

    public function comments(): BelongsToMany
    {
        // return $this->belongsToMany(User::class, 'comments', 'foto_id', 'user_id')->withPivot('description')->orderBy('comments.created_at', 'desc');
        return $this->belongsToMany(User::class, 'comments', 'foto_id', 'user_id')
            ->join('users as u', 'u.id', '=', 'comments.user_id') // Menentukan alias 'u' untuk tabel users
            ->select('comments.*', 'u.username') // Menggunakan alias 'u' untuk mengakses kolom 'username'
            ->withPivot('description')
            ->orderBy('comments.created_at', 'desc');
    }
}
