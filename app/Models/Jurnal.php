<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;

    protected $table = 'jurnals';
    protected $fillable = ['id_user','judul', 'gambar', 'deskripsi'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user'); // Pastikan 'user_id' ada di tabel jurnals
    }

 
}