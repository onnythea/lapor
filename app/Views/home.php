<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="p-4 p-md-5 mb-4 rounded-3 bg-white">
    <div class="row align-items-center">
        <div class="col-md-7">
            <h1 class="fw-bold text-brand">Sampaikan Pengaduan &amp; Aspirasi Anda</h1>
            <p class="lead text-muted">Layanan pengaduan bagi mahasiswa dan masyarakat umum. Aman, transparan, dan dapat dilacak dengan nomor tiket.</p>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?= site_url('/auth/register') ?>" class="btn btn-brand"><i class="bi bi-pencil-square"></i> Buat Laporan</a>
                <a href="<?= site_url('/tracking') ?>" class="btn btn-outline-secondary"><i class="bi bi-search"></i> Lacak Tiket</a>
            </div>
        </div>
        <div class="col-md-5 text-center d-none d-md-block">
            <i class="bi bi-megaphone-fill text-brand" style="font-size:8rem;"></i>
        </div>
    </div>
</div>

<h2 class="h4 mb-3">Kategori Pengaduan</h2>
<div class="row g-3">
    <?php foreach ($categories as $c): ?>
        <div class="col-sm-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="h6 fw-bold mb-1"><?= esc($c['name']) ?></h3>
                    <p class="text-muted small mb-0"><?= esc($c['description']) ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?= $this->endSection() ?>
