<?php

namespace App\Controllers\Pejabat;

use App\Controllers\BaseController;
use App\Entities\Pegawai;
use App\Entities\Pengajuan;
use App\Models\LampiranPengajuanModel;
use App\Models\PegawaiModel;
use App\Models\PengajuanModel;
use CodeIgniter\HTTP\ResponseInterface;

class PengajuanController extends BaseController
{
    protected PengajuanModel $pengajuanModel;
    protected PegawaiModel $pegawaiModel;
    protected LampiranPengajuanModel $lampiranModel;

    public function __construct()
    {
        $this->pengajuanModel = new PengajuanModel();
        $this->pegawaiModel   = new PegawaiModel();
        $this->lampiranModel  = new LampiranPengajuanModel();
    }

    /**
     * Menampilkan daftar pengajuan kenaikan untuk review Pejabat Penilai
     */
    public function index(): string
    {
        $keyword = (string) ($this->request->getGet('q') ?? $this->request->getGet('search') ?? '');
        $jenis   = (string) ($this->request->getGet('jenis') ?? '');
        $status  = (string) ($this->request->getGet('status') ?? '');

        $pengajuans = $this->pengajuanModel->getWithPegawai($keyword, $jenis, $status);

        $data = [
            'title'          => 'Pengajuan Kenaikan',
            'activeMenu'     => 'pengajuan',
            'pengajuans'     => $pengajuans,
            'keyword'        => $keyword,
            'selectedJenis'  => $jenis,
            'selectedStatus' => $status,
        ];

        return view('pejabat/pengajuan/index', $data);
    }

    /**
     * Menyetujui pengajuan kenaikan
     */
    public function approve(int $id): ResponseInterface
    {
        /** @var Pengajuan|null $pengajuan */
        $pengajuan = $this->pengajuanModel->find($id);
        if (! $pengajuan instanceof Pengajuan) {
            return redirect()->to('/pejabat/pengajuan')->with('error', 'Data pengajuan tidak ditemukan.');
        }

        $pengajuan->status  = 'Disetujui';
        $pengajuan->catatan = $this->request->getPost('catatan') ? (string) $this->request->getPost('catatan') : 'Disetujui oleh Pejabat Penilai';

        $this->pengajuanModel->save($pengajuan);

        return redirect()->to('/pejabat/pengajuan')->with('success', 'Pengajuan kenaikan berhasil disetujui.');
    }

    /**
     * Menolak pengajuan kenaikan
     */
    public function reject(int $id): ResponseInterface
    {
        /** @var Pengajuan|null $pengajuan */
        $pengajuan = $this->pengajuanModel->find($id);
        if (! $pengajuan instanceof Pengajuan) {
            return redirect()->to('/pejabat/pengajuan')->with('error', 'Data pengajuan tidak ditemukan.');
        }

        $catatan = trim((string) $this->request->getPost('catatan'));
        if ($catatan === '') {
            $catatan = 'Pengajuan ditolak oleh Pejabat Penilai.';
        }

        $pengajuan->status  = 'Ditolak';
        $pengajuan->catatan = $catatan;

        $this->pengajuanModel->save($pengajuan);

        return redirect()->to('/pejabat/pengajuan')->with('success', 'Pengajuan kenaikan telah ditolak.');
    }

    /**
     * Endpoint detail data pengajuan beserta berkas lampirannya
     */
    public function detail(int $id): ResponseInterface
    {
        /** @var Pengajuan|null $pengajuan */
        $pengajuan = $this->pengajuanModel->find($id);
        if (! $pengajuan instanceof Pengajuan) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Pengajuan tidak ditemukan.',
            ]);
        }

        /** @var Pegawai|null $pegawai */
        $pegawai   = $this->pegawaiModel->find($pengajuan->pegawai_id);
        $lampirans = $this->lampiranModel->getByPengajuanId($id);

        $lampiranData = [];
        foreach ($lampirans as $l) {
            $lampiranData[] = [
                'id'           => $l->id,
                'nama_dokumen' => $l->nama_dokumen,
                'view_url'     => base_url('lampiran/view/' . $l->id),
                'download_url' => base_url('lampiran/download/' . $l->id),
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'id'             => $pengajuan->id,
                'pegawai_id'     => $pengajuan->pegawai_id,
                'nama'           => $pegawai instanceof Pegawai ? $pegawai->nama : '-',
                'nip'            => $pegawai instanceof Pegawai ? $pegawai->nip : '-',
                'jabatan'        => $pegawai instanceof Pegawai ? $pegawai->jabatan : '-',
                'masa_kerja'     => $pegawai instanceof Pegawai ? $pegawai->masa_kerja : '-',
                'jenis_kenaikan' => $pengajuan->jenis_kenaikan,
                'status'         => $pengajuan->status,
                'catatan'        => $pengajuan->catatan ?? '-',
                'created_at'     => $pengajuan->created_at ? (string) $pengajuan->created_at : '-',
                'lampiran'       => $lampiranData,
            ],
        ]);
    }
}
