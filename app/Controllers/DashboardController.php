<?php

namespace App\Controllers;

use App\Entities\Pegawai;
use App\Models\PegawaiModel;
use App\Models\PengajuanModel;

class DashboardController extends BaseController
{
    protected PegawaiModel $pegawaiModel;
    protected PengajuanModel $pengajuanModel;

    public function __construct()
    {
        $this->pegawaiModel   = new PegawaiModel();
        $this->pengajuanModel = new PengajuanModel();
    }

    public function index(): string
    {
        $role   = (string) session()->get('role');
        $userId = (int) session()->get('user_id');

        if ($role === 'ADMIN') {
            $pegawaiCount   = $this->pegawaiModel->countAllResults();
            $pengajuanCount = $this->pengajuanModel->countAllResults();
            $laporanCount   = $this->pengajuanModel->whereIn('status', ['Disetujui', 'Ditolak'])->countAllResults();

            $data = [
                'title'          => 'Dashboard',
                'activeMenu'     => 'dashboard',
                'role'           => $role,
                'pegawaiCount'   => sprintf('%02d', $pegawaiCount),
                'pengajuanCount' => sprintf('%02d', $pengajuanCount),
                'laporanCount'   => sprintf('%02d', $laporanCount),
            ];

            return view('admin/dashboard', $data);
        }

        if ($role === 'PEJABAT_PENILAI') {
            $menungguCount  = $this->pengajuanModel->where('status', 'Menunggu')->countAllResults();
            $disetujuiCount = $this->pengajuanModel->where('status', 'Disetujui')->countAllResults();
            $ditolakCount   = $this->pengajuanModel->where('status', 'Ditolak')->countAllResults();
            $pendingList    = $this->pengajuanModel->getWithPegawai(status: 'Menunggu');

            $data = [
                'title'               => 'Dashboard',
                'activeMenu'          => 'dashboard',
                'role'                => $role,
                'menungguCount'       => sprintf('%02d', $menungguCount),
                'disetujuiCount'      => sprintf('%02d', $disetujuiCount),
                'ditolakCount'        => sprintf('%02d', $ditolakCount),
                'pendingPengajuans'   => array_slice($pendingList, 0, 5),
            ];

            return view('pejabat/dashboard', $data);
        }

        // Pegawai Role
        /** @var Pegawai|null $pegawai */
        $pegawai          = $this->pegawaiModel->findByUserId($userId);
        $totalPengajuan   = 0;
        $menungguCount    = 0;
        $disetujuiCount   = 0;
        $ditolakCount     = 0;
        $recentPengajuans = [];

        if ($pegawai instanceof Pegawai && $pegawai->id !== null) {
            $pegawaiId        = (int) $pegawai->id;
            $totalPengajuan   = $this->pengajuanModel->where('pegawai_id', $pegawaiId)->countAllResults();
            $menungguCount    = $this->pengajuanModel->where('pegawai_id', $pegawaiId)->where('status', 'Menunggu')->countAllResults();
            $disetujuiCount   = $this->pengajuanModel->where('pegawai_id', $pegawaiId)->where('status', 'Disetujui')->countAllResults();
            $ditolakCount     = $this->pengajuanModel->where('pegawai_id', $pegawaiId)->where('status', 'Ditolak')->countAllResults();
            $allPengajuans    = $this->pengajuanModel->getWithPegawai(pegawaiId: $pegawaiId);
            $recentPengajuans = array_slice($allPengajuans, 0, 5);
        }

        $data = [
            'title'            => 'Dashboard',
            'activeMenu'       => 'dashboard',
            'role'             => $role,
            'pegawai'          => $pegawai,
            'totalPengajuan'   => sprintf('%02d', $totalPengajuan),
            'menungguCount'    => sprintf('%02d', $menungguCount),
            'disetujuiCount'   => sprintf('%02d', $disetujuiCount),
            'ditolakCount'     => sprintf('%02d', $ditolakCount),
            'recentPengajuans' => $recentPengajuans,
        ];

        return view('pegawai/dashboard', $data);
    }
}
