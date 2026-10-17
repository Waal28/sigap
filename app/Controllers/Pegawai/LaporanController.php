<?php

namespace App\Controllers\Pegawai;

use App\Controllers\BaseController;
use App\Entities\Pegawai;
use App\Models\PegawaiModel;
use App\Models\PengajuanModel;

class LaporanController extends BaseController
{
    protected PengajuanModel $pengajuanModel;
    protected PegawaiModel $pegawaiModel;

    public function __construct()
    {
        $this->pengajuanModel = new PengajuanModel();
        $this->pegawaiModel   = new PegawaiModel();
    }

    /**
     * Menampilkan data laporan riwayat pengajuan milik pegawai yang sedang login
     */
    public function index(): string
    {
        $userId  = (int) session()->get('user_id');
        /** @var Pegawai|null $pegawai */
        $pegawai = $this->pegawaiModel->findByUserId($userId);

        $keyword       = $this->request->getGet('q');
        $keyword       = is_string($keyword) ? trim($keyword) : null;
        $jenisKenaikan = $this->request->getGet('jenis');
        $jenisKenaikan = is_string($jenisKenaikan) ? trim($jenisKenaikan) : null;
        $statusParam   = $this->request->getGet('status');
        $statusParam   = is_string($statusParam) ? trim($statusParam) : null;

        $pengajuans = [];
        if ($pegawai instanceof Pegawai && $pegawai->id !== null) {
            $pengajuans = $this->pengajuanModel->getWithPegawai(
                keyword: $keyword,
                jenisKenaikan: $jenisKenaikan,
                status: $statusParam,
                pegawaiId: (int) $pegawai->id
            );
        }

        $data = [
            'title'          => 'Laporan',
            'activeMenu'     => 'laporan',
            'pengajuans'     => $pengajuans,
            'pegawai'        => $pegawai,
            'keyword'        => $keyword,
            'selectedJenis'  => $jenisKenaikan,
            'selectedStatus' => $statusParam,
            'role'           => 'PEGAWAI',
        ];

        return view('pegawai/laporan/index', $data);
    }
}
