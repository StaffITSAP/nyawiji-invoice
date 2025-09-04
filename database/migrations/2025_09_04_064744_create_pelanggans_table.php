<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('telepon')->nullable();
            $table->string('npwp')->nullable();
            $table->text('alamat_tagihan')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['nama']);
        });
    }
    public function down(): void { Schema::dropIfExists('pelanggan'); }
};
