<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    protected $fillable = ['nama', 'deskripsi'];

    protected $table = 'divisis';

    public function users()
{
    return $this->hasMany(User::class);
}
}

