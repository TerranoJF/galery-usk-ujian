<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', // Tambahkan 'name' ke dalam properti fillable
        'description',
        'user_id',
    ];
}
