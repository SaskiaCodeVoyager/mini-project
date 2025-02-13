<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id_user'; // Set the primary key column name

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'username', 'email', 'password', 'role',
        'asal_sekolah', 'jenis_kelamin', 'tempat_lahir',
        'alamat', 'no_hp', 'alamat_sekolah', 'no_hp_sekolah',
        'divisi_id', 'foto_pribadi',
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }
    
    public function absens() {
        return $this->hasMany(Absen::class, 'id_user');
    }

    public function absen() {
        return $this->hasOne(Absen::class, 'id_izin');
    }



public function izins()
{
    return $this->hasMany(Izin::class, 'id_user');
}


}
