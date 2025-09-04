<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pajak', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('tipe', ['persentase','nominal'])->default('persentase');
            $table->decimal('nilai', 18, 4)->default(0); // 11.0000% / 10000.0000 nominal
            $table->boolean('default')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pajak'); }
};
