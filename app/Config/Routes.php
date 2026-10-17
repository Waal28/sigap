<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', static function () {
    if (session()->get('isLoggedIn')) {
        return redirect()->to('/dashboard');
    }

    return redirect()->to('/login');
});

// Authentication routes
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::processLogin');
$routes->get('logout', 'AuthController::logout');

// Protected application routes
$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes) {
    $routes->get('dashboard', 'DashboardController::index');

    // Dynamic Pengajuan dispatcher based on role
    $routes->get('pengajuan', static function () {
        $role = (string) session()->get('role');
        if ($role === 'ADMIN') {
            return redirect()->to('/admin/pengajuan');
        }
        if ($role === 'PEJABAT_PENILAI') {
            return redirect()->to('/pejabat/pengajuan');
        }

        return redirect()->to('/pegawai/pengajuan');
    });

    // Dynamic Laporan dispatcher based on role
    $routes->get('laporan', static function () {
        $role = (string) session()->get('role');
        if ($role === 'ADMIN') {
            return redirect()->to('/admin/laporan');
        }
        if ($role === 'PEJABAT_PENILAI') {
            return redirect()->to('/pejabat/laporan');
        }

        return redirect()->to('/pegawai/laporan');
    });

    // 1. Admin-only routes
    $routes->group('admin', ['filter' => 'role:ADMIN'], static function (RouteCollection $routes) {
        // Data Pegawai
        $routes->get('pegawai', 'Admin\PegawaiController::index');
        $routes->post('pegawai/store', 'Admin\PegawaiController::store');
        $routes->post('pegawai/update/(:num)', 'Admin\PegawaiController::update/$1');
        $routes->get('pegawai/delete/(:num)', 'Admin\PegawaiController::delete/$1');
        $routes->post('pegawai/delete/(:num)', 'Admin\PegawaiController::delete/$1');
        $routes->get('pegawai/detail/(:num)', 'Admin\PegawaiController::detail/$1');

        // Pengajuan Kenaikan (Admin)
        $routes->get('pengajuan', 'Admin\PengajuanController::index');
        $routes->get('pengajuan/delete/(:num)', 'Admin\PengajuanController::delete/$1');
        $routes->post('pengajuan/delete/(:num)', 'Admin\PengajuanController::delete/$1');
        $routes->get('pengajuan/lampiran/(:num)', 'Admin\PengajuanController::lampiran/$1');

        // Laporan (Admin)
        $routes->get('laporan', 'Admin\LaporanController::index');
    });

    // 2. Pejabat Penilai-only routes
    $routes->group('pejabat', ['filter' => 'role:PEJABAT_PENILAI'], static function (RouteCollection $routes) {
        $routes->get('pengajuan', 'Pejabat\PengajuanController::index');
        $routes->post('pengajuan/approve/(:num)', 'Pejabat\PengajuanController::approve/$1');
        $routes->post('pengajuan/reject/(:num)', 'Pejabat\PengajuanController::reject/$1');
        $routes->get('pengajuan/detail/(:num)', 'Pejabat\PengajuanController::detail/$1');

        // Laporan (Pejabat Penilai)
        $routes->get('laporan', 'Pejabat\LaporanController::index');
    });

    // 3. Pegawai-only routes
    $routes->group('pegawai', ['filter' => 'role:PEGAWAI'], static function (RouteCollection $routes) {
        $routes->get('pengajuan', 'Pegawai\PengajuanController::index');
        $routes->post('pegawai/store', 'Pegawai\PengajuanController::store');
        $routes->post('pengajuan/store', 'Pegawai\PengajuanController::store');

        // Laporan (Pegawai)
        $routes->get('laporan', 'Pegawai\LaporanController::index');
    });

    // 4. Dokumen lampiran preview, download & AJAX list
    $routes->get('lampiran/view/(:num)', 'LampiranController::view/$1');
    $routes->get('lampiran/download/(:num)', 'LampiranController::download/$1');
    $routes->get('lampiran/list/(:num)', 'LampiranController::list/$1');

    // Route alias
    $routes->get('pegawai', static function () {
        return redirect()->to('/admin/pegawai');
    }, ['filter' => 'role:ADMIN']);
});
