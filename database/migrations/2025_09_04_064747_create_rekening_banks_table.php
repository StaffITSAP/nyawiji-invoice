<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rekening_bank', function (Blueprint $table) {
            $table->id();
            $table->string('bank');
            $table->string('nama_rekening');
            $table->string('nomor_rekening');
            $table->string('swift')->nullable();
            $table->string('cabang')->nullable();
            $table->boolean('default')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('rekening_bank'); }
};
