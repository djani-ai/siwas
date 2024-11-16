<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('alat_kerjas', function (Blueprint $table) {
            $table->string('sort')->nullable()->nullOnDelete();
            // $table->foreignId('kec_id')->nullable()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alat_kerjas', function (Blueprint $table) {
            //
        });
    }
};
