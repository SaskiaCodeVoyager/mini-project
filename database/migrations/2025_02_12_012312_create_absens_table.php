<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up() {
        Schema::create('absens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->onDelete('restrict');
            $table->date('tanggal')->default(DB::raw('CURRENT_DATE'));
            $table->enum('keterangan', ['masuk', 'izin', 'sakit', 'alpa'])->default('alpa');
            $table->time('absen_masuk')->nullable();
            $table->time('absen_pulang')->nullable();
            $table->timestamps();
        });
    }
    
    public function down() {
        Schema::dropIfExists('absens');
    }
};