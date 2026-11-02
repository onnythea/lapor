<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Histori Pengaduan Saya</h1>
    <a href="<?= site_url('/pengaduan/buat') ?>" class="btn btn-brand btn-sm"><i class="bi bi-plus-lg"></i> Buat Laporan</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nomor Tiket</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Tanggal</th><th></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($items === []): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada laporan.</td></tr>
                <?php endif; ?>
                <?php foreach ($items as $item): ?>
                    <?php [$label, $class] = status_badge($item['status']); ?>
                    <tr>
                        <td><code><?= esc($item['ticket_number']) ?></code></td>
                        <td><?= esc($item['title']) ?></td>
                        <td><?= esc($item['category_name']) ?></td>
                        <td><span class="badge <?= $class ?>"><?= $label ?></span></td>
                        <td class="small text-muted"><?= esc($item['created_at']) ?></td>
                        <td><a class="btn btn-sm btn-outline-secondary" href="<?= site_url('pengaduan/detail/' . $item['ticket_number']) ?>">Detail</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3"><?= $pager->links() ?></div>
<?= $this->endSection() ?>
