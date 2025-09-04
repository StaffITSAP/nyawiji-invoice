<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('faktur', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique()->nullable(); // diisi otomatis saat terbit
            $table->foreignId('pelanggan_id')->constrained('pelanggan');
            $table->date('tanggal');
            $table->date('jatuh_tempo')->nullable();
            $table->enum('status', ['draft', 'terbit', 'lunas', 'batal'])->default('draft')->index();

            $table->string('kode_mata_uang')->default('IDR');
            $table->decimal('kurs', 18, 6)->default(1);

            $table->decimal('subtotal', 18, 2)->default(0);
            $table->decimal('total_diskon', 18, 2)->default(0);
            $table->decimal('total_pajak', 18, 2)->default(0);
            $table->decimal('total_bersih', 18, 2)->default(0);

            $table->foreignId('pajak_id')->nullable()->constrained('pajak');
            $table->foreignId('diskon_id')->nullable()->constrained('diskon');
            $table->foreignId('metode_pembayaran_id')->nullable()->constrained('metode_pembayaran');
            $table->foreignId('rekening_bank_id')->nullable()->constrained('rekening_bank');

            $table->text('catatan')->nullable();
            $table->json('meta')->nullable(); // snapshot pengaturan saat terbit
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('faktur_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faktur_id')->constrained('faktur')->cascadeOnDelete();
            $table->foreignId('produk_id')->nullable()->constrained('produk');
            $table->string('deskripsi');
            $table->decimal('kuantitas', 18, 4)->default(1);
            $table->string('satuan')->nullable();
            $table->decimal('harga_satuan', 18, 2)->default(0);
            $table->foreignId('pajak_id')->nullable()->constrained('pajak');
            $table->foreignId('diskon_id')->nullable()->constrained('diskon');
            $table->decimal('jumlah', 18, 2)->default(0); // nilai setelah diskon & pajak
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('faktur_syarat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faktur_id')->constrained('faktur')->cascadeOnDelete();
            $table->foreignId('syarat_ketentuan_id')->constrained('syarat_ketentuan');
            $table->integer('urutan')->default(0);
            $table->timestamps();
            $table->unique(['faktur_id', 'syarat_ketentuan_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('faktur_syarat');
        Schema::dropIfExists('faktur_item');
        Schema::dropIfExists('faktur');
    }
};
