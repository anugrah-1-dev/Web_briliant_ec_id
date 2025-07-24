<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Sementara simpan data kolom jika perlu (kalau kamu ingin)
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('gender');
            $table->dropColumn('kategori');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->enum('gender', ['PUTRA', 'PUTRI'])->after('nomor_kamar');
            $table->enum('kategori', ['VVIP', 'VIP', 'BARACK'])->after('gender');
        });
    }

    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('gender');
            $table->dropColumn('kategori');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->enum('gender', ['putra', 'putri'])->after('nomor_kamar');
            $table->enum('kategori', ['VVIP', 'VIP', 'Barack'])->after('gender');
        });
    }
};
