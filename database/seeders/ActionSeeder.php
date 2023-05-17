<?php

namespace Database\Seeders;

use App\Models\Action;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $actions = [
            'Dashboard',
            'KKB-List',
            'KKB-upload bukti pembayaran',
            'KKB-konfirmasi bukti pembayaran',
            'KKB-atur tanggal ketersediaan unit',
            'KKB-atur tanggal penyerahan unit',
            'KKB-upload berkas STNK',
            'KKB-upload berkas Polis',
            'KKB-upload berkas BPKB',
            'KKB-konfirmasi berkas STNK',
            'KKB-konfirmasi berkas Polis',
            'KKB-konfirmasi berkas BPKB',
            'KKB-atur imbal jasa',
            'KKB-detail data',
            'Role-List data',
            'Role-Tambah data',
            'Role-Edit data',
            'Role-Hapus data',
            'Pengguna-List data',
            'Pengguna-Tambah data',
            'Pengguna-Edit data',
            'Pengguna-Hapus data',
            'Vendor-List data',
            'Vendor-Tambah data',
            'Vendor-Edit data',
            'Vendor-Hapus data',
            'Kategori Dokumen-List data',
            'Kategori Dokumen-Tambah data',
            'Kategori Dokumen-Edit data',
            'Kategori Dokumen-Hapus data',
            'Imbal Jasa-List data',
            'Imbal Jasa-Tambah data',
            'Imbal Jasa-Edit data',
            'Imbal Jasa-Hapus data',
            'Template Notifikasi-List data',
            'Template Notifikasi-Tambah data',
            'Template Notifikasi-Edit data',
            'Template Notifikasi-Hapus data',
            'Log aktifitas-List data',
            'Laporan-List data',
            'Laporan-Export data',
            'Target-List data',
            'Target-Tambah data',
            'Target-Edit data',
            'Target-Hapus data',
            'Notifikasi-List data',
        ];

        for ($i=0; $i < count($actions); $i++) { 
            $createAction = new Action();
            $createAction->name = $actions[$i];
            $createAction->save();
        }
    }
}
