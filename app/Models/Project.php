<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'nama_project', 'deskripsi', 'tahap_id'];

    public function tahap()
    {
        return $this->belongsTo(Tahap::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
