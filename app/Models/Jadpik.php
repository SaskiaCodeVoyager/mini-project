<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadpik extends Model
{
    use HasFactory;

    protected $fillable = ['nama_siswa', 'hari_id'];

    public function hari()
    {
        return $this->belongsTo(Hari::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'jadpik_user', 'jadpik_id', 'id_user');
    }
}
