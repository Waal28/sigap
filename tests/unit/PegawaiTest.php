<?php

use App\Entities\Pegawai;
use App\Models\PegawaiModel;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class PegawaiTest extends CIUnitTestCase
{
    public function testPegawaiEntityProperties(): void
    {
        $pegawai = new Pegawai([
            'id'         => 10,
            'user_id'    => 20,
            'nip'        => '199001012015011001',
            'nama'       => 'Siti Aminah, S.Pd',
            'jabatan'    => 'Gol III/B',
            'masa_kerja' => '5 Tahun',
        ]);

        $this->assertSame(10, $pegawai->id);
        $this->assertSame(20, $pegawai->user_id);
        $this->assertSame('199001012015011001', $pegawai->nip);
        $this->assertSame('Siti Aminah, S.Pd', $pegawai->nama);
        $this->assertSame('Gol III/B', $pegawai->jabatan);
        $this->assertSame('5 Tahun', $pegawai->masa_kerja);
    }

    public function testPegawaiModelAllowedFields(): void
    {
        $model = new PegawaiModel();
        $this->assertSame('pegawais', $model->table);
        $this->assertSame('id', $model->primaryKey);
        $this->assertContains('nama', $model->allowedFields);
        $this->assertContains('nip', $model->allowedFields);
        $this->assertContains('jabatan', $model->allowedFields);
        $this->assertContains('masa_kerja', $model->allowedFields);
        $this->assertContains('user_id', $model->allowedFields);
    }
}
