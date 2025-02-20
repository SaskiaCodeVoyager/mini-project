<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tahap extends Model
{
    protected $fillable = ['nama', 'deskripsi'];

    // App\Models\Tahap.php
    public function users()
    {
        return $this->belongsToMany(User::class, 'tahap_user', 'tahap_id', 'id_user');
    }

    public function tahap()
    {
        return $this->hasone(Tahap::class);
    }

}


