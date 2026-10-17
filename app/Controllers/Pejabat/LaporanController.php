<?php

namespace App\Controllers\Pejabat;

use App\Controllers\BaseController;
use App\Models\PengajuanModel;

class LaporanController extends BaseController
{
    protected PengajuanModel $pengajuanModel;

    public function __construct()
    {
        $this->pengajuanModel = new PengajuanModel();
    }

    /**
     * Menampilkan data laporan riwayat pengajuan yang sudah diproses (Pejabat Penilai)
     */
    public function index(): string
    {
        $keyword       = $this->request->getGet('q');
        $keyword       = is_string($keyword) ? trim($keyword) : null;
        $jenisKenaikan = $this->request->getGet('jenis');
        $jenisKenaikan = is_string($jenisKenaikan) ? trim($jenisKenaikan) : null;
        $statusParam   = $this->request->getGet('status');
        $statusParam   = is_string($statusParam) ? trim($statusParam) : null;

        // Default: Pengajuan yang sudah diproses oleh Pejabat Penilai (Disetujui / Ditolak)
        if ($statusParam === 'Disetujui' || $statusParam === 'Ditolak') {
            $statusFilter = $statusParam;
        } else {
            $statusFilter = ['Disetujui', 'Ditolak'];
        }

        $pengajuans = $this->pengajuanModel->getWithPegawai(
            keyword: $keyword,
            jenisKenaikan: $jenisKenaikan,
            status: $statusFilter
        );

        $data = [
            'title'          => 'Laporan',
            'activeMenu'     => 'laporan',
            'pengajuans'     => $pengajuans,
            'keyword'        => $keyword,
            'selectedJenis'  => $jenisKenaikan,
            'selectedStatus' => $statusParam,
            'role'           => 'PEJABAT_PENILAI',
        ];

        return view('pejabat/laporan/index', $data);
    }
}
