<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('divisis', function (Blueprint $table) {
            $table->id(); // id divisi
            $table->string('nama', 100);
            $table->string('deskripsi');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user'); // Primary Key
            $table->string('username', 100)->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'member'])->default('member');
            $table->string('email', 150)->unique();
            $table->string('asal_sekolah', 150)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('tempat_lahir', 150)->nullable();
            $table->string('alamat', 150)->nullable();
            $table->string('no_hp', 150)->nullable();
            $table->string('alamat_sekolah', 150)->nullable();
            $table->string('no_hp_sekolah', 150)->nullable();
            // Definisi foreign key dengan restrict pada penghapusan data
            $table->foreignId('divisi_id')->nullable()->constrained('divisis')->onDelete('restrict');
            $table->string('foto_pribadi', 150)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('divisis');
    }
};
