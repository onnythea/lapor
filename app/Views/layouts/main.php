<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'LAPOR Aspirasi') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --brand: #0d4d92; }
        body { background: #f5f7fb; }
        .navbar-brand { font-weight: 700; letter-spacing: .3px; }
        .bg-brand { background-color: var(--brand) !important; }
        .text-brand { color: var(--brand) !important; }
        .btn-brand { background-color: var(--brand); color:#fff; }
        .btn-brand:hover { background-color: #0a3c72; color:#fff; }
        .card { border: none; box-shadow: 0 1px 3px rgba(16,24,40,.08); }
        .timeline { list-style:none; padding-left: 0; }
        .timeline li { border-left: 2px solid #dee2e6; padding: 0 0 1rem 1rem; position: relative; }
        .timeline li::before { content:''; width:12px; height:12px; background: var(--brand); border-radius:50%; position:absolute; left:-7px; top:2px; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-brand">
    <div class="container">
        <a class="navbar-brand" href="<?= site_url('/') ?>">
            <i class="bi bi-megaphone-fill me-1"></i>LAPOR Aspirasi
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"
                aria-controls="nav" aria-expanded="false" aria-label="Buka menu navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <li class="nav-item"><a class="nav-link" href="<?= site_url('/tracking') ?>">Lacak Tiket</a></li>
                <?php $role = session('role'); ?>
                <?php if (session('is_logged_in')): ?>
                    <?php if (in_array($role, ['mahasiswa', 'umum'], true)): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= site_url('/pengaduan') ?>">Pengaduan Saya</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= site_url('/pengaduan/buat') ?>">Buat Laporan</a></li>
                    <?php endif; ?>
                    <?php if (in_array($role, ['petugas', 'admin'], true)): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= site_url('/petugas/dashboard') ?>">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= site_url('/petugas/laporan') ?>">Kelola Laporan</a></li>
                    <?php endif; ?>
                    <?php if ($role === 'admin'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Admin</a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= site_url('/admin/users') ?>">Manajemen User</a></li>
                                <li><a class="dropdown-item" href="<?= site_url('/admin/kategori') ?>">Kategori</a></li>
                                <li><a class="dropdown-item" href="<?= site_url('/admin/rekap') ?>">Cetak & Ekspor</a></li>
                                <li><a class="dropdown-item" href="<?= site_url('/admin/log-aktivitas') ?>">Log Aktivitas</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="btn btn-light btn-sm ms-lg-2" href="<?= site_url('/auth/logout') ?>">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= site_url('/auth/login') ?>">Login</a></li>
                    <li class="nav-item"><a class="btn btn-light btn-sm ms-lg-2" href="<?= site_url('/auth/register') ?>">Daftar</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container my-4">
    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= esc(session()->getFlashdata('message')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                <?php foreach ((array) session()->getFlashdata('errors') as $e): ?>
                    <li><?= esc($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</main>

<footer class="bg-white border-top py-3 mt-5">
    <div class="container text-center text-muted small">
        &copy; <?= date('Y') ?> Sistem Layanan Pengaduan Masyarakat &amp; Mahasiswa
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
