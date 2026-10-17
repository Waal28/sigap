<?php

use App\Entities\LampiranPengajuan;
use App\Entities\Pengajuan;
use App\Models\LampiranPengajuanModel;
use App\Models\PengajuanModel;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class PengajuanTest extends CIUnitTestCase
{
    public function testPengajuanEntity(): void
    {
        $pengajuan = new Pengajuan([
            'id'             => 1,
            'pegawai_id'     => 3,
            'jenis_kenaikan' => 'Gaji',
            'status'         => 'Menunggu',
            'catatan'        => 'Berkas lengkap',
        ]);

        $this->assertSame(1, $pengajuan->id);
        $this->assertSame(3, $pengajuan->pegawai_id);
        $this->assertSame('Gaji', $pengajuan->jenis_kenaikan);
        $this->assertSame('Menunggu', $pengajuan->status);
        $this->assertSame('Berkas lengkap', $pengajuan->catatan);
    }

    public function testLampiranPengajuanEntity(): void
    {
        $lampiran = new LampiranPengajuan([
            'id'           => 5,
            'pengajuan_id' => 1,
            'nama_dokumen' => 'Surat Rekomendasi',
            'file_path'    => 'uploads/lampiran/sample.pdf',
        ]);

        $this->assertSame(5, $lampiran->id);
        $this->assertSame(1, $lampiran->pengajuan_id);
        $this->assertSame('Surat Rekomendasi', $lampiran->nama_dokumen);
        $this->assertSame('uploads/lampiran/sample.pdf', $lampiran->file_path);
    }

    public function testPengajuanModelAllowedFields(): void
    {
        $model = new PengajuanModel();
        $this->assertSame('pengajuans', $model->table);
        $this->assertContains('pegawai_id', $model->allowedFields);
        $this->assertContains('jenis_kenaikan', $model->allowedFields);
        $this->assertContains('status', $model->allowedFields);
        $this->assertContains('catatan', $model->allowedFields);
    }

    public function testLampiranPengajuanModelAllowedFields(): void
    {
        $model = new LampiranPengajuanModel();
        $this->assertSame('lampiran_pengajuans', $model->table);
        $this->assertContains('pengajuan_id', $model->allowedFields);
        $this->assertContains('nama_dokumen', $model->allowedFields);
        $this->assertContains('file_path', $model->allowedFields);
    }

    public function testGetWithPegawaiMethodExists(): void
    {
        $model = new PengajuanModel();
        $this->assertTrue(method_exists($model, 'getWithPegawai'));
        $reflection = new \ReflectionMethod($model, 'getWithPegawai');
        $params     = $reflection->getParameters();
        $this->assertCount(4, $params);
        $this->assertSame('keyword', $params[0]->getName());
        $this->assertSame('jenisKenaikan', $params[1]->getName());
        $this->assertSame('status', $params[2]->getName());
        $this->assertSame('pegawaiId', $params[3]->getName());
    }
}
