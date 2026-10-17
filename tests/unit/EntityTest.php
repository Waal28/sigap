<?php

use App\Entities\LampiranPengajuan;
use App\Entities\Pegawai;
use App\Entities\Pengajuan;
use App\Entities\User;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class EntityTest extends CIUnitTestCase
{
    public function testUserEntityAndPasswordHashing(): void
    {
        $user = new User([
            'nip'      => '19900101',
            'role'     => 'ADMIN',
        ]);
        $user->setPassword('secret123');

        $this->assertSame('19900101', $user->nip);
        $this->assertSame('ADMIN', $user->role);
        $this->assertTrue(password_verify('secret123', $user->password));
    }

    public function testPegawaiEntity(): void
    {
        $pegawai = new Pegawai([
            'user_id'    => 1,
            'nip'        => '199502152020121002',
            'nama'       => 'Budi Santoso',
            'jabatan'    => 'Gol II/A',
            'masa_kerja' => '2 Tahun',
        ]);

        $this->assertSame(1, $pegawai->user_id);
        $this->assertSame('Budi Santoso', $pegawai->nama);
        $this->assertSame('Gol II/A', $pegawai->jabatan);
        $this->assertSame('2 Tahun', $pegawai->masa_kerja);
    }

    public function testPengajuanEntity(): void
    {
        $pengajuan = new Pengajuan([
            'pegawai_id'     => 5,
            'jenis_kenaikan' => 'Gaji',
            'status'         => 'Menunggu',
            'catatan'        => 'Pengajuan berkala',
        ]);

        $this->assertSame(5, $pengajuan->pegawai_id);
        $this->assertSame('Gaji', $pengajuan->jenis_kenaikan);
        $this->assertSame('Menunggu', $pengajuan->status);
        $this->assertSame('Pengajuan berkala', $pengajuan->catatan);
    }

    public function testLampiranPengajuanEntity(): void
    {
        $lampiran = new LampiranPengajuan([
            'pengajuan_id' => 10,
            'nama_dokumen' => 'SK_Pangkat_Terakhir.pdf',
            'file_path'    => 'uploads/lampiran/sk_123.pdf',
        ]);

        $this->assertSame(10, $lampiran->pengajuan_id);
        $this->assertSame('SK_Pangkat_Terakhir.pdf', $lampiran->nama_dokumen);
        $this->assertSame('uploads/lampiran/sk_123.pdf', $lampiran->file_path);
    }
}
