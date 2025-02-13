<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('absens', function (Blueprint $table) {
            $table->unsignedBigInteger('id_izin')->nullable()->after('id_user'); // Tambahkan kolom
        });
    }
    
    public function down()
    {
        Schema::table('absens', function (Blueprint $table) {
            $table->dropColumn('id_izin');
        });
    }
    
};
