<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('penomoran', function (Blueprint $table) {
            $table->id();
            $table->string('konteks'); // mis. 'faktur'
            $table->string('prefix')->default('NWS');
            $table->integer('tahun')->index();
            $table->integer('bulan')->nullable(); // jika format bulanan
            $table->unsignedBigInteger('counter')->default(0);
            $table->timestamps();
            $table->unique(['konteks','prefix','tahun','bulan']);
        });
    }
    public function down(): void { Schema::dropIfExists('penomoran'); }
};
