<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hari extends Model
{
    protected $fillable = ['nama'];

    public function jadpiks()
    {
        return $this->hasMany(Jadpik::class);
    }
}
