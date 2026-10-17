<?php

namespace App\Database\Seeds;

use App\Entities\Pegawai;
use App\Entities\User;
use App\Models\PegawaiModel;
use App\Models\UserModel;
use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userModel    = new UserModel();
        $pegawaiModel = new PegawaiModel();

        // 1. Admin
        $admin = $userModel->findByNip('admin');
        if (! $admin) {
            $adminUser = new User([
                'nip'      => 'admin',
                'password' => 'password123',
                'role'     => 'ADMIN',
            ]);
            $adminId = $userModel->insert($adminUser, true);

            $pegawaiModel->insert(new Pegawai([
                'user_id'    => $adminId,
                'nip'        => 'admin',
                'nama'       => 'Administrator',
                'jabatan'    => 'Pengelola Sistem',
                'masa_kerja' => '-',
            ]));
        }

        // 2. Pejabat Penilai
        $penilai = $userModel->findByNip('198001012005011001');
        if (! $penilai) {
            $penilaiUser = new User([
                'nip'      => '198001012005011001',
                'password' => 'password123',
                'role'     => 'PEJABAT_PENILAI',
            ]);
            $penilaiId = $userModel->insert($penilaiUser, true);

            $pegawaiModel->insert(new Pegawai([
                'user_id'    => $penilaiId,
                'nip'        => '198001012005011001',
                'nama'       => 'Drs. H. Ahmad, M.Si',
                'jabatan'    => 'Kepala Dinas',
                'masa_kerja' => '15 Tahun',
            ]));
        }

        // 3. Pegawai Contoh
        $pegawai = $userModel->findByNip('199502152020121002');
        if (! $pegawai) {
            $pegawaiUser = new User([
                'nip'      => '199502152020121002',
                'password' => 'password123',
                'role'     => 'PEGAWAI',
            ]);
            $pegawaiId = $userModel->insert($pegawaiUser, true);

            $pegawaiModel->insert(new Pegawai([
                'user_id'    => $pegawaiId,
                'nip'        => '199502152020121002',
                'nama'       => 'Budi Santoso',
                'jabatan'    => 'Gol II/A',
                'masa_kerja' => '2 Tahun',
            ]));
        }
    }
}
