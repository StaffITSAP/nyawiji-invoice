<?php

namespace Database\Seeders;

use App\Models\{Pajak, Diskon, RekeningBank, MetodePembayaran, PengaturanInvoice, SyaratKetentuan, Pelanggan, Produk};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\{Role, Permission};
use App\Models\User;

class AwalInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        // Roles & Permissions
        $permissions = [
            'kelola pelanggan', 'kelola produk', 'kelola pajak', 'kelola diskon',
            'kelola rekening', 'kelola metode pembayaran',
            'kelola syarat', 'kelola pengaturan invoice',
            'lihat faktur', 'buat faktur', 'edit faktur', 'hapus faktur', 'terbitkan faktur', 'unduh faktur',
        ];

        foreach ($permissions as $p) Permission::firstOrCreate(['name' => $p]);

        $superadmin = Role::firstOrCreate(['name' => 'superadmin']);
        $admin      = Role::firstOrCreate(['name' => 'admin']);
        $staf       = Role::firstOrCreate(['name' => 'staf']);
        $superadmin->syncPermissions(Permission::all());
        $admin->syncPermissions([
            'kelola pelanggan','kelola produk','kelola pajak','kelola diskon','kelola rekening','kelola metode pembayaran',
            'kelola syarat','lihat faktur','buat faktur','edit faktur','terbitkan faktur','unduh faktur'
        ]);
        $staf->syncPermissions(['lihat faktur','buat faktur','unduh faktur']);

        // Superadmin user
        $user = User::firstOrCreate(
            ['email' => env('SEED_SUPERADMIN_EMAIL','superadmin@nyawiji.test')],
            [
                'name' => env('SEED_SUPERADMIN_NAME','Superadmin'),
                'password' => Hash::make(env('SEED_SUPERADMIN_PASSWORD','password123')),
                'email_verified_at' => now(),
            ]
        );
        $user->assignRole($superadmin);

        // Data awal
        $ppn = Pajak::firstOrCreate(['nama'=>'PPN 11%'], ['tipe'=>'persentase','nilai'=>11,'default'=>true]);
        $disk = Diskon::firstOrCreate(['nama'=>'Tanpa Diskon'], ['tipe'=>'nominal','nilai'=>0,'default'=>true]);
        $rek = RekeningBank::firstOrCreate(['bank'=>'BCA','nama_rekening'=>'Nyawiji Web Solutions','nomor_rekening'=>'1234567890','default'=>true]);
        MetodePembayaran::firstOrCreate(['nama'=>'Transfer Bank','aktif'=>true]);

        PengaturanInvoice::firstOrCreate([], [
            'nama_perusahaan'=>'Nyawiji Web Solutions',
            'alamat_perusahaan'=>'Jl. Contoh No. 123, Jakarta',
            'email_perusahaan'=>'billing@nyawiji.id',
            'telepon_perusahaan'=>'+62 812-0000-0000',
            'prefix_nomor'=>'NWS',
            'format_nomor'=>'{PREFIX}/{YYYY}/{MM}/{COUNTER:4}',
            'pajak_id'=>$ppn->id,
            'diskon_id'=>$disk->id,
            'rekening_bank_id'=>$rek->id,
            'footer_html'=>'<p>Terima kasih atas kepercayaan Anda.</p>',
            'template_opsi'=>[
                'tampilkan_logo'=>true,
                'tampilkan_tabel_pajak'=>true,
                'tampilkan_qr'=>false,
                'kolom'=>['deskripsi','kuantitas','satuan','harga','diskon','pajak','jumlah'],
            ],
        ]);

        // contoh pelanggan & produk
        Pelanggan::firstOrCreate(['nama'=>'PT Contoh Sejahtera','email'=>'finance@contoh.id','alamat_tagihan'=>'Gedung Contoh Lt. 3, Jakarta']);
        Produk::firstOrCreate(['nama'=>'Jasa Pengembangan Website','satuan'=>'paket','harga_default'=>15000000,'aktif'=>true]);
    }
}
