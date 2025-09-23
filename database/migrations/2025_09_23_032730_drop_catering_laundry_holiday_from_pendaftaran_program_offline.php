<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran_program_offline', function (Blueprint $table) {
            // Pastikan foreign key di-drop dulu sebelum kolom
            $table->dropForeign(['id_catering']);
            $table->dropForeign(['id_laundry']);
            $table->dropForeign(['id_holiday']);

            $table->dropColumn(['id_catering', 'id_laundry', 'id_holiday']);
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_program_offline', function (Blueprint $table) {
            $table->unsignedBigInteger('id_catering')->nullable()->after('id');
            $table->unsignedBigInteger('id_laundry')->nullable()->after('id_catering');
            $table->unsignedBigInteger('id_holiday')->nullable()->after('id_laundry');

            $table->foreign('id_catering')
                ->references('id')
                ->on('catering_packages')
                ->onDelete('set null');

            $table->foreign('id_laundry')
                ->references('id')
                ->on('laundry_packages')
                ->onDelete('set null');

            $table->foreign('id_holiday')
                ->references('id')
                ->on('holiday_packages')
                ->onDelete('set null');
        });
    }
};
