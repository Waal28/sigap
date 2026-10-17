<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\Pengajuan;
use App\Models\LampiranPengajuanModel;
use App\Models\PengajuanModel;
use CodeIgniter\HTTP\ResponseInterface;

class PengajuanController extends BaseController
{
    protected PengajuanModel $pengajuanModel;
    protected LampiranPengajuanModel $lampiranModel;

    public function __construct()
    {
        $this->pengajuanModel = new PengajuanModel();
        $this->lampiranModel  = new LampiranPengajuanModel();
    }

    /**
     * Menampilkan daftar semua pengajuan kenaikan (Hak Akses Admin)
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

        return view('admin/pengajuan/index', $data);
    }

    /**
     * Menghapus data pengajuan beserta lampirannya
     */
    public function delete(int $id): ResponseInterface
    {
        /** @var Pengajuan|null $pengajuan */
        $pengajuan = $this->pengajuanModel->find($id);
        if (! $pengajuan instanceof Pengajuan) {
            return redirect()->to('/admin/pengajuan')->with('error', 'Data pengajuan tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Hapus file fisik lampiran
            $lampirans = $this->lampiranModel->getByPengajuanId($id);
            foreach ($lampirans as $lampiran) {
                $fullPath = WRITEPATH . $lampiran->file_path;
                if (is_file($fullPath)) {
                    @unlink($fullPath);
                }
            }

            // Hapus data lampiran dan pengajuan
            $this->lampiranModel->where('pengajuan_id', $id)->delete();
            $this->pengajuanModel->delete($id);

            $db->transCommit();

            return redirect()->to('/admin/pengajuan')->with('success', 'Data pengajuan kenaikan berhasil dihapus.');
        } catch (\Throwable $e) {
            $db->transRollback();

            return redirect()->to('/admin/pengajuan')->with('error', 'Gagal menghapus pengajuan: ' . $e->getMessage());
        }
    }

    /**
     * Mengambil daftar lampiran pengajuan (format JSON)
     */
    public function lampiran(int $id): ResponseInterface
    {
        $lampirans = $this->lampiranModel->getByPengajuanId($id);
        $data      = [];

        foreach ($lampirans as $l) {
            $data[] = [
                'id'           => $l->id,
                'nama_dokumen' => $l->nama_dokumen,
                'file_url'     => base_url('lampiran/view/' . $l->id),
                'download_url' => base_url('lampiran/download/' . $l->id),
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $data,
        ]);
    }
}
