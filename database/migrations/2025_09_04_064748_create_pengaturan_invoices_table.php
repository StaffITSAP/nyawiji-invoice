<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pengaturan_invoice', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan')->default('Nyawiji Web Solutions');
            $table->string('alamat_perusahaan')->nullable();
            $table->string('email_perusahaan')->nullable();
            $table->string('telepon_perusahaan')->nullable();
            $table->string('npwp_perusahaan')->nullable();

            $table->string('prefix_nomor')->default('NWS');
            $table->string('format_nomor')->default('{PREFIX}/{YYYY}/{MM}/{COUNTER:4}');
            $table->string('format_tanggal')->default('d M Y');
            $table->string('kode_mata_uang')->default('IDR');
            $table->integer('pembulatan')->default(0); // 0=tanpa, 1=ke ratusan, etc.

            $table->string('warna_utama')->default('#0ea5e9');
            $table->string('warna_teks')->default('#111827');
            $table->string('font_family')->default('Inter, ui-sans-serif');

            $table->string('logo_path')->nullable(); // storage path
            $table->text('footer_html')->nullable();

            $table->foreignId('pajak_id')->nullable()->constrained('pajak');
            $table->foreignId('diskon_id')->nullable()->constrained('diskon');
            $table->foreignId('rekening_bank_id')->nullable()->constrained('rekening_bank');

            $table->json('template_opsi')->nullable(); // kolom yang ditampilkan, heading, watermark, dll
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pengaturan_invoice'); }
};
