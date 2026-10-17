<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\Pegawai;
use App\Entities\User;
use App\Models\PegawaiModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class PegawaiController extends BaseController
{
    protected PegawaiModel $pegawaiModel;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->pegawaiModel = new PegawaiModel();
        $this->userModel    = new UserModel();
    }

    /**
     * Menampilkan daftar data pegawai
     */
    public function index(): string
    {
        $keyword = (string) ($this->request->getGet('q') ?? $this->request->getGet('search') ?? '');
        $jabatan = (string) ($this->request->getGet('jabatan') ?? '');

        $pegawais = $this->pegawaiModel->search($keyword, $jabatan);
        $jabatans = $this->pegawaiModel->getDistinctJabatan();

        $data = [
            'title'           => 'Data Pegawai',
            'activeMenu'      => 'pegawai',
            'pegawais'        => $pegawais,
            'jabatans'        => $jabatans,
            'keyword'         => $keyword,
            'selectedJabatan' => $jabatan,
        ];

        return view('admin/pegawai/index', $data);
    }

    /**
     * Menyimpan data pegawai baru beserta akun login
     */
    public function store(): ResponseInterface
    {
        $rules = [
            'nama'       => 'required|min_length[3]|max_length[150]',
            'nip'        => 'required|min_length[4]|max_length[50]|is_unique[users.nip]',
            'password'   => 'required|min_length[6]',
            'jabatan'    => 'required|max_length[100]',
            'masa_kerja' => 'required|max_length[50]',
        ];

        $messages = [
            'nama' => [
                'required'   => 'Nama pegawai wajib diisi.',
                'min_length' => 'Nama minimal terdiri dari 3 karakter.',
            ],
            'nip' => [
                'required'  => 'NIP wajib diisi.',
                'is_unique' => 'NIP sudah terdaftar di sistem. Gunakan NIP lain.',
            ],
            'password' => [
                'required'   => 'Kata sandi wajib diisi.',
                'min_length' => 'Kata sandi minimal 6 karakter.',
            ],
            'jabatan' => [
                'required' => 'Jabatan/Golongan wajib diisi.',
            ],
            'masa_kerja' => [
                'required' => 'Masa kerja wajib diisi.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $nama      = trim((string) $this->request->getPost('nama'));
        $nip       = trim((string) $this->request->getPost('nip'));
        $password  = (string) $this->request->getPost('password');
        $jabatan   = trim((string) $this->request->getPost('jabatan'));
        $masaKerja = trim((string) $this->request->getPost('masa_kerja'));

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // 1. Buat akun user
            $user = new User([
                'nip'      => $nip,
                'password' => $password,
                'role'     => 'PEGAWAI',
            ]);

            $userId = $this->userModel->insert($user, true);
            if (! $userId) {
                throw new \RuntimeException('Gagal menyimpan data akun pengguna.');
            }

            // 2. Buat data pegawai
            $pegawai = new Pegawai([
                'user_id'    => (int) $userId,
                'nip'        => $nip,
                'nama'       => $nama,
                'jabatan'    => $jabatan,
                'masa_kerja' => $masaKerja,
            ]);

            $saved = $this->pegawaiModel->insert($pegawai);
            if (! $saved) {
                throw new \RuntimeException('Gagal menyimpan data pegawai.');
            }

            $db->transCommit();

            return redirect()->to('/admin/pegawai')->with('success', 'Data pegawai ' . esc($nama) . ' berhasil ditambahkan.');
        } catch (\Throwable $e) {
            $db->transRollback();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan pegawai: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui data pegawai
     */
    public function update(int $id): ResponseInterface
    {
        /** @var Pegawai|null $pegawai */
        $pegawai = $this->pegawaiModel->find($id);
        if (! $pegawai instanceof Pegawai) {
            return redirect()->to('/admin/pegawai')->with('error', 'Data pegawai tidak ditemukan.');
        }

        $userId = (int) $pegawai->user_id;
        /** @var User|null $user */
        $user = $this->userModel->find($userId);

        $rules = [
            'nama'       => 'required|min_length[3]|max_length[150]',
            'nip'        => "required|min_length[4]|max_length[50]|is_unique[users.nip,id,{$userId}]",
            'jabatan'    => 'required|max_length[100]',
            'masa_kerja' => 'required|max_length[50]',
            'password'   => 'permit_empty|min_length[6]',
        ];

        $messages = [
            'nama' => [
                'required'   => 'Nama pegawai wajib diisi.',
                'min_length' => 'Nama minimal terdiri dari 3 karakter.',
            ],
            'nip' => [
                'required'  => 'NIP wajib diisi.',
                'is_unique' => 'NIP sudah digunakan oleh akun lain.',
            ],
            'password' => [
                'min_length' => 'Kata sandi minimal 6 karakter jika ingin diganti.',
            ],
            'jabatan' => [
                'required' => 'Jabatan/Golongan wajib diisi.',
            ],
            'masa_kerja' => [
                'required' => 'Masa kerja wajib diisi.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $nama      = trim((string) $this->request->getPost('nama'));
        $nip       = trim((string) $this->request->getPost('nip'));
        $password  = (string) $this->request->getPost('password');
        $jabatan   = trim((string) $this->request->getPost('jabatan'));
        $masaKerja = trim((string) $this->request->getPost('masa_kerja'));

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // 1. Update user jika NIP atau password berubah
            if ($user instanceof User) {
                $user->nip = $nip;
                if (! empty($password)) {
                    $user->setPassword($password);
                }
                $this->userModel->save($user);
            }

            // 2. Update pegawai
            $pegawai->nip        = $nip;
            $pegawai->nama       = $nama;
            $pegawai->jabatan    = $jabatan;
            $pegawai->masa_kerja = $masaKerja;

            $this->pegawaiModel->save($pegawai);

            $db->transCommit();

            return redirect()->to('/admin/pegawai')->with('success', 'Data pegawai ' . esc($nama) . ' berhasil diperbarui.');
        } catch (\Throwable $e) {
            $db->transRollback();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui pegawai: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data pegawai beserta akun user
     */
    public function delete(int $id): ResponseInterface
    {
        /** @var Pegawai|null $pegawai */
        $pegawai = $this->pegawaiModel->find($id);
        if (! $pegawai instanceof Pegawai) {
            return redirect()->to('/admin/pegawai')->with('error', 'Data pegawai tidak ditemukan.');
        }

        $nama   = $pegawai->nama;
        $userId = (int) $pegawai->user_id;

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Hapus pegawai
            $this->pegawaiModel->delete($id);

            // Hapus user terkait jika ada
            if ($userId > 0) {
                $this->userModel->delete($userId);
            }

            $db->transCommit();

            return redirect()->to('/admin/pegawai')->with('success', 'Data pegawai ' . esc($nama) . ' berhasil dihapus.');
        } catch (\Throwable $e) {
            $db->transRollback();

            return redirect()->to('/admin/pegawai')->with('error', 'Gagal menghapus pegawai: ' . $e->getMessage());
        }
    }

    /**
     * Mengambil detail pegawai dalam format JSON (untuk modal detail)
     */
    public function detail(int $id): ResponseInterface
    {
        /** @var Pegawai|null $pegawai */
        $pegawai = $this->pegawaiModel->find($id);
        if (! $pegawai instanceof Pegawai) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Data pegawai tidak ditemukan.',
            ]);
        }

        /** @var User|null $user */
        $user = $this->userModel->find($pegawai->user_id);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'id'         => $pegawai->id,
                'user_id'    => $pegawai->user_id,
                'nip'        => $pegawai->nip,
                'nama'       => $pegawai->nama,
                'jabatan'    => $pegawai->jabatan,
                'masa_kerja' => $pegawai->masa_kerja,
                'role'       => $user instanceof User ? $user->role : 'PEGAWAI',
                'created_at' => $pegawai->created_at ? (string) $pegawai->created_at : '-',
                'updated_at' => $pegawai->updated_at ? (string) $pegawai->updated_at : '-',
            ],
        ]);
    }
}
