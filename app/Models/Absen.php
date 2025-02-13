<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Absen extends Model {
    use HasFactory;

    protected $fillable = ['id_user', 'tanggal', 'keterangan', 'absen_masuk', 'absen_pulang'];

    /**
     * Relasi ke model User (Setiap absen dimiliki oleh satu user)
     */
    public function user() {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function izin() {
        return $this->belongsTo(Izin::class, 'id_izin');
    }
    /**
     * Mencatat absen masuk
     */
    public static function absenMasuk($userId) {
        $now = Carbon::now();

        return self::firstOrCreate(
            ['id_user' => $userId, 'tanggal' => $now->toDateString()],
            ['absen_masuk' => $now->toTimeString(), 'keterangan' => 'masuk']
        );
    }

    /**
     * Mencatat absen pulang
     */
    public static function absenPulang($userId) {
        $now = Carbon::now();
        $absen = self::where('id_user', $userId)
                     ->whereDate('tanggal', $now->toDateString())
                     ->first();

        if ($absen && !$absen->absen_pulang) {
            $absen->update(['absen_pulang' => $now->toTimeString()]);
        }

        return $absen;
    }
}
