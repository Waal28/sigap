<?php

namespace App\Controllers;

use App\Entities\LampiranPengajuan;
use App\Models\LampiranPengajuanModel;
use CodeIgniter\HTTP\ResponseInterface;

class LampiranController extends BaseController
{
    protected LampiranPengajuanModel $lampiranModel;

    public function __construct()
    {
        $this->lampiranModel = new LampiranPengajuanModel();
    }

    /**
     * Preview dokumen PDF langsung di browser
     */
    public function view(int $id): ResponseInterface
    {
        /** @var LampiranPengajuan|null $lampiran */
        $lampiran = $this->lampiranModel->find($id);
        if (! $lampiran instanceof LampiranPengajuan) {
            return $this->response->setStatusCode(404)->setBody('Dokumen tidak ditemukan.');
        }

        $filePath = WRITEPATH . $lampiran->file_path;
        if (! is_file($filePath)) {
            return $this->response->setStatusCode(404)->setBody('File fisik dokumen tidak ditemukan di server.');
        }

        $fileContent = (string) file_get_contents($filePath);
        $fileName    = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $lampiran->nama_dokumen) . '.pdf';

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $fileName . '"')
            ->setBody($fileContent);
    }

    /**
     * Download dokumen PDF
     */
    public function download(int $id): ResponseInterface
    {
        /** @var LampiranPengajuan|null $lampiran */
        $lampiran = $this->lampiranModel->find($id);
        if (! $lampiran instanceof LampiranPengajuan) {
            return $this->response->setStatusCode(404)->setBody('Dokumen tidak ditemukan.');
        }

        $filePath = WRITEPATH . $lampiran->file_path;
        if (! is_file($filePath)) {
            return $this->response->setStatusCode(404)->setBody('File fisik dokumen tidak ditemukan di server.');
        }

        $fileName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $lampiran->nama_dokumen) . '.pdf';

        return $this->response->download($filePath, null)->setFileName($fileName);
    }

    /**
     * Dapatkan daftar lampiran pengajuan dalam format JSON untuk modal preview
     */
    public function list(int $pengajuanId): ResponseInterface
    {
        $lampirans = $this->lampiranModel->getByPengajuanId($pengajuanId);

        $data = [];
        foreach ($lampirans as $l) {
            $data[] = [
                'id'           => $l->id,
                'nama_dokumen' => $l->nama_dokumen,
                'view_url'     => base_url('lampiran/view/' . $l->id),
                'download_url' => base_url('lampiran/download/' . $l->id),
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $data,
        ]);
    }
}
