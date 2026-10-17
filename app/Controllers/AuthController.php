<?php

namespace App\Controllers;

use App\Models\PegawaiModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    protected UserModel $userModel;
    protected PegawaiModel $pegawaiModel;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->pegawaiModel = new PegawaiModel();
    }

    /**
     * Menampilkan halaman login
     */
    public function login(): string|ResponseInterface
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    /**
     * Memproses autentikasi pengguna
     */
    public function processLogin(): ResponseInterface
    {
        $rules = [
            'nip'      => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'NIP dan Kata Sandi wajib diisi.');
        }

        $nip      = (string) $this->request->getPost('nip');
        $password = (string) $this->request->getPost('password');

        $user = $this->userModel->findByNip($nip);
        if (! $user) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nomor Induk Pegawai tidak terdaftar.');
        }

        if (! password_verify($password, $user->password)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kata sandi yang Anda masukkan salah.');
        }

        $pegawai = $user->id !== null ? $this->pegawaiModel->findByUserId((int) $user->id) : null;
        $nama    = $pegawai ? $pegawai->nama : ($user->role === 'ADMIN' ? 'Admin' : $user->nip);
        $jabatan = $pegawai ? $pegawai->jabatan : '';

        session()->set([
            'user_id'    => $user->id,
            'nip'        => $user->nip,
            'role'       => $user->role,
            'nama'       => $nama,
            'jabatan'    => $jabatan,
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/dashboard')->with('success', 'Selamat datang, ' . esc($nama) . '!');
    }

    /**
     * Keluar dari sistem
     */
    public function logout(): ResponseInterface
    {
        session()->destroy();

        return redirect()->to('/login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
