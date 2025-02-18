<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    // use HasFactory;

    protected $fillable = ['nama_project', 'deskripsi', 'tahap_id', 'id_user'];

    // public function tahap()
    // {
    //     return $this->belongsTo(Tahap::class);
    // }
    public function users()
    {
        return $this->belongsToMany(User::class, 'project_users');
    }
    
}
