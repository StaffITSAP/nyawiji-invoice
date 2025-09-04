<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('diskon', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('tipe', ['persentase','nominal'])->default('persentase');
            $table->decimal('nilai', 18, 4)->default(0);
            $table->boolean('default')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('diskon'); }
};
