<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    use HasFactory;

    protected $table = 'izins';
    
    protected $fillable = ['id_user', 'dari_tanggal', 'sampai_tanggal', 'bukti', 'deskripsi'];


    public function absens() {
        return $this->hasOne(Absen::class, 'id_izin');
    }

    public function user()
{
    return $this->belongsTo(User::class, 'id_user');
}


}
