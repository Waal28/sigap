<?php

namespace App\Controllers\Pegawai;

use App\Controllers\BaseController;
use App\Entities\LampiranPengajuan;
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
     * Menampilkan form pengajuan kenaikan pangkat / gaji
     */
    public function index(): string|ResponseInterface
    {
        $userId = (int) session()->get('user_id');
        $pegawai = $this->pegawaiModel->findByUserId($userId);

        if (! $pegawai instanceof Pegawai) {
            return redirect()->to('/dashboard')->with('error', 'Profil data pegawai Anda belum terdaftar.');
        }

        $riwayat = [];
        if ($pegawai->id !== null) {
            $riwayat = $this->pengajuanModel->getByPegawaiId($pegawai->id);
        }

        $data = [
            'title'      => 'Pengajuan Kenaikan',
            'activeMenu' => 'pengajuan',
            'pegawai'    => $pegawai,
            'riwayat'    => $riwayat,
        ];

        return view('pegawai/pengajuan/create', $data);
    }

    /**
     * Menyimpan berkas dan data pengajuan kenaikan
     */
    public function store(): ResponseInterface
    {
        $userId  = (int) session()->get('user_id');
        $pegawai = $this->pegawaiModel->findByUserId($userId);

        if (! $pegawai instanceof Pegawai || $pegawai->id === null) {
            return redirect()->to('/dashboard')->with('error', 'Profil pegawai tidak valid.');
        }

        $rules = [
            'jenis_kenaikan' => 'required|in_list[Gaji,Pangkat]',
        ];

        // Validasi opsional untuk setiap file PDF jika diunggah
        $documentFields = [
            'surat_rekomendasi'          => 'Surat Rekomendasi',
            'daftar_riwayat_hidup'       => 'Daftar Riwayat Hidup',
            'surat_pernyataan_disiplin'  => 'Surat Pernyataan Tidak Sedang Dalam Hukuman Disiplin',
            'kartu_keluarga'             => 'Kartu Keluarga',
            'scan_skp'                   => 'Scan SKP',
            'sk_terakhir'                => 'Fotokopi SK Terakhir',
            'ijazah_transkrip'           => 'Fotokopi Ijazah & Transkrip Nilai',
            'surat_keterangan_sehat'     => 'Surat Keterangan Sehat',
            'bukti_pelatihan'            => 'Bukti Pelatihan',
            'formulir_usulan'            => 'Formulir Usulan Kenaikan Pangkat',
        ];

        foreach (array_keys($documentFields) as $fieldKey) {
            $rules[$fieldKey] = "permit_empty|max_size[{$fieldKey},2048]|ext_in[{$fieldKey},pdf]";
        }

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $jenisKenaikan = (string) $this->request->getPost('jenis_kenaikan');

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // 1. Simpan pengajuan
            $pengajuan = new Pengajuan([
                'pegawai_id'     => $pegawai->id,
                'jenis_kenaikan' => $jenisKenaikan,
                'status'         => 'Menunggu',
                'catatan'        => null,
            ]);

            $pengajuanId = $this->pengajuanModel->insert($pengajuan, true);
            if (! $pengajuanId) {
                throw new \RuntimeException('Gagal menyimpan pengajuan kenaikan.');
            }

            // 2. Simpan lampiran berkas PDF
            $targetPath = WRITEPATH . 'uploads/lampiran';
            if (! is_dir($targetPath)) {
                mkdir($targetPath, 0755, true);
            }

            $uploadedCount = 0;
            foreach ($documentFields as $fieldKey => $label) {
                $file = $this->request->getFile($fieldKey);
                if ($file && $file->isValid() && ! $file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move($targetPath, $newName);

                    $lampiran = new LampiranPengajuan([
                        'pengajuan_id' => (int) $pengajuanId,
                        'nama_dokumen' => $label,
                        'file_path'    => 'uploads/lampiran/' . $newName,
                    ]);

                    $this->lampiranModel->insert($lampiran);
                    $uploadedCount++;
                }
            }

            $db->transCommit();

            $pesan = 'Pengajuan kenaikan ' . esc($jenisKenaikan) . ' berhasil diajukan (' . $uploadedCount . ' dokumen terlampir) dan sedang menunggu proses verifikasi.';

            return redirect()->to('/pegawai/pengajuan')->with('success', $pesan);
        } catch (\Throwable $e) {
            $db->transRollback();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses pengajuan: ' . $e->getMessage());
        }
    }
}
