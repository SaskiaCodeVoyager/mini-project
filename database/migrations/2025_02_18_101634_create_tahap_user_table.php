<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_xx_xx_create_tahap_user_table.php
public function up()
{
    Schema::create('tahap_user', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tahap_id')->onDelete('cascade');
        $table->foreignId('id_user')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahap_user');
    }
};
