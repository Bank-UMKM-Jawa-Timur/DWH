<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('notification_templates')->insert([
            'title' => 'Data pengajuan baru',
            'content' => 'Terdapat data pengajuan baru.',
            'action_id' => 2,
            'all_role' => 1
        ]);
        
        \DB::table('notification_templates')->insert([
            'title' => 'Tanggal Ketersediaan Unit',
            'content' => 'Tanggal ketersediaan unit telah diatur.',
            'action_id' => 6,
            'role_id' => '1,2'
        ]);
        
        \DB::table('notification_templates')->insert([
            'title' => 'Bukti pembayaran',
            'content' => 'Upload bukti pembayaran telah dilakukan.',
            'action_id' => 4,
            'role_id' => '1,3'
        ]);
        
        \DB::table('notification_templates')->insert([
            'title' => 'Konfirmasi Bukti Pembayaran',
            'content' => 'Bukti bukti pembayaran telah dikonfirmasi.',
            'action_id' => 5,
            'role_id' => '1,2'
        ]);
        
        \DB::table('notification_templates')->insert([
            'title' => 'Tanggal Penyerahan Unit',
            'content' => 'Tanggal penyerahan unit telah diatur.',
            'action_id' => 7,
            'role_id' => '1,2'
        ]);
        
        \DB::table('notification_templates')->insert([
            'title' => 'Upload Tagihan',
            'content' => 'Tagihan telah diupload oleh vendor.',
            'action_id' => 50,
        ]);
        
        \DB::table('notification_templates')->insert([
            'title' => 'Kofirmasi Bukti Penyerahan Unit',
            'content' => 'Bukti penyerahan unit telah dikonfirmasi.',
            'action_id' => 8,
            'role_id' => '1,3'
        ]);

        \DB::table('notification_templates')->insert([
            'title' => 'Upload STNK',
            'content' => 'STNK telah diupload.',
            'action_id' => 9,
            'role_id' => '1,2'
        ]);

        \DB::table('notification_templates')->insert([
            'title' => 'Upload Polis',
            'content' => 'Polis telah diupload.',
            'action_id' => 10,
            'role_id' => '1,2'
        ]);

        \DB::table('notification_templates')->insert([
            'title' => 'Upload BPKB',
            'content' => 'BPKB telah diupload.',
            'action_id' => 11,
            'role_id' => '1,2'
        ]);

        \DB::table('notification_templates')->insert([
            'title' => 'Konfirmasi STNK',
            'content' => 'STNK telah dikonfirmasi.',
            'action_id' => 12,
            'role_id' => '1,3'
        ]);

        \DB::table('notification_templates')->insert([
            'title' => 'Konfirmasi Polis',
            'content' => 'Polis telah dikonfirmasi.',
            'action_id' => 13,
            'role_id' => '1,3'
        ]);

        \DB::table('notification_templates')->insert([
            'title' => 'Konfirmasi BPKB',
            'content' => 'BPKB telah dikonfirmasi.',
            'action_id' => 14,
            'role_id' => '1,3'
        ]);

        \DB::table('notification_templates')->insert([
            'title' => 'Upload imbal jasa',
            'content' => 'Berkas imbal jasa telah diupload oleh cabang.',
            'action_id' => 15,
        ]);

        \DB::table('notification_templates')->insert([
            'title' => 'Konfirmasi imbal jasa',
            'content' => 'Berkas imbal jasa telah dikonfirmasi oleh vendor.',
            'action_id' => 50,
        ]);
    }
}
