<?php

declare(strict_types=1);

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ---------------------------------------------------------------------
// Halaman Publik
// ---------------------------------------------------------------------
$routes->get('/', 'Home::index');

// Tracking publik menggunakan nomor tiket (tanpa login)
$routes->group('tracking', static function (RouteCollection $routes): void {
    $routes->get('/', 'Tracking::index');
    $routes->post('cari', 'Tracking::search');
    $routes->get('(:segment)', 'Tracking::show/$1');
});

// ---------------------------------------------------------------------
// Autentikasi
// ---------------------------------------------------------------------
$routes->group('auth', static function (RouteCollection $routes): void {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::attemptLogin');
    $routes->get('register', 'Auth::register');
    $routes->post('register', 'Auth::attemptRegister');
    $routes->get('logout', 'Auth::logout');
});

// ---------------------------------------------------------------------
// Area Pelapor (mahasiswa / umum) — butuh login
// ---------------------------------------------------------------------
$routes->group('pengaduan', ['filter' => 'auth:mahasiswa,umum,petugas,admin'], static function (RouteCollection $routes): void {
    $routes->get('/', 'Complaint::index');
    $routes->get('buat', 'Complaint::create');
    $routes->post('simpan', 'Complaint::store');
    $routes->get('detail/(:segment)', 'Complaint::show/$1');
    $routes->post('(:num)/kirim', 'Complaint::submit/$1');       // Draft -> Terkirim
    $routes->post('(:num)/rating', 'Complaint::rate/$1');        // Umpan balik setelah selesai
    $routes->get('unduh/(:num)', 'Complaint::download/$1');      // Unduh lampiran (akses terkontrol)
});

// ---------------------------------------------------------------------
// Area Petugas / Admin Unit — butuh role petugas atau admin
// ---------------------------------------------------------------------
$routes->group('petugas', ['filter' => 'auth:petugas,admin'], static function (RouteCollection $routes): void {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    $routes->get('laporan', 'Admin\ComplaintManager::index');
    $routes->get('laporan/(:segment)', 'Admin\ComplaintManager::show/$1');
    $routes->post('laporan/(:num)/status', 'Admin\ComplaintManager::updateStatus/$1');
    $routes->post('laporan/(:num)/tanggapan', 'Admin\ComplaintManager::respond/$1');
    $routes->post('laporan/(:num)/disposisi', 'Admin\ComplaintManager::dispose/$1');
});

// ---------------------------------------------------------------------
// Area Super Admin — butuh role admin
// ---------------------------------------------------------------------
$routes->group('admin', ['filter' => 'auth:admin'], static function (RouteCollection $routes): void {
    // Manajemen user
    $routes->get('users', 'Admin\UserManager::index');
    $routes->get('users/baru', 'Admin\UserManager::create');
    $routes->post('users', 'Admin\UserManager::store');
    $routes->get('users/(:num)/edit', 'Admin\UserManager::edit/$1');
    $routes->post('users/(:num)', 'Admin\UserManager::update/$1');
    $routes->post('users/(:num)/reset-password', 'Admin\UserManager::resetPassword/$1');
    $routes->post('users/(:num)/hapus', 'Admin\UserManager::delete/$1');

    // Manajemen kategori
    $routes->get('kategori', 'Admin\CategoryManager::index');
    $routes->post('kategori', 'Admin\CategoryManager::store');
    $routes->post('kategori/(:num)', 'Admin\CategoryManager::update/$1');
    $routes->post('kategori/(:num)/hapus', 'Admin\CategoryManager::delete/$1');

    // Laporan & ekspor
    $routes->get('rekap', 'Admin\Report::index');
    $routes->get('rekap/pdf', 'Admin\Report::exportPdf');
    $routes->get('rekap/excel', 'Admin\Report::exportExcel');

    // Log aktivitas (audit trail)
    $routes->get('log-aktivitas', 'Admin\ActivityLog::index');
});
