<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <a href="<?= site_url('/tracking') ?>" class="btn btn-link px-0 mb-2"><i class="bi bi-arrow-left"></i> Lacak tiket lain</a>
        <?php if ($complaint === null): ?>
            <div class="alert alert-warning">Tiket <strong><?= esc($ticket) ?></strong> tidak ditemukan. Periksa kembali nomor tiket Anda.</div>
        <?php else: ?>
            <?php [$label, $class] = status_badge($complaint['status']); ?>
            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between flex-wrap gap-2">
                        <h1 class="h4 text-brand mb-0"><?= esc($complaint['ticket_number']) ?></h1>
                        <span class="badge <?= $class ?> fs-6"><?= $label ?></span>
                    </div>
                    <hr>
                    <dl class="row mb-0">
                        <dt class="col-sm-3">Judul</dt><dd class="col-sm-9"><?= esc($complaint['title']) ?></dd>
                        <dt class="col-sm-3">Kategori</dt><dd class="col-sm-9"><?= esc($complaint['category_name']) ?></dd>
                        <dt class="col-sm-3">Pelapor</dt><dd class="col-sm-9"><?= esc($complaint['reporter_name'] ?? 'Anonim') ?></dd>
                        <dt class="col-sm-3">Tanggal</dt><dd class="col-sm-9"><?= esc($complaint['created_at']) ?></dd>
                    </dl>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body p-4">
                    <h2 class="h6 mb-3">Riwayat Tanggapan</h2>
                    <?php if ($responses === []): ?>
                        <p class="text-muted mb-0">Belum ada tanggapan dari petugas.</p>
                    <?php else: ?>
                        <ul class="timeline">
                            <?php foreach ($responses as $r): ?>
                                <li>
                                    <div class="text-muted small"><?= esc($r['created_at']) ?></div>
                                    <div style="white-space:pre-line;"><?= esc($r['response_text']) ?></div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
