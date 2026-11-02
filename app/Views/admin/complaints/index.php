<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="h4 mb-3">Kelola Laporan Masuk</h1>

<form method="get" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="q" class="form-control" placeholder="Cari tiket / judul" value="<?= esc($keyword) ?>">
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">Semua Status</option>
            <?php foreach (['pending' => 'Terkirim', 'processing' => 'Diproses', 'completed' => 'Selesai', 'rejected' => 'Ditolak'] as $k => $v): ?>
                <option value="<?= $k ?>" <?= $status === $k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2"><button class="btn btn-brand w-100">Filter</button></div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Tiket</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Tanggal</th><th></th></tr></thead>
            <tbody>
                <?php if ($items === []): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data.</td></tr>
                <?php endif; ?>
                <?php foreach ($items as $item): ?>
                    <?php [$label, $class] = status_badge($item['status']); ?>
                    <tr>
                        <td><code><?= esc($item['ticket_number']) ?></code></td>
                        <td><?= esc($item['title']) ?><?= (int) $item['is_anonymous'] === 1 ? ' <span class="badge bg-secondary">Anonim</span>' : '' ?></td>
                        <td><?= esc($item['category_name']) ?></td>
                        <td><span class="badge <?= $class ?>"><?= $label ?></span></td>
                        <td class="small text-muted"><?= esc($item['created_at']) ?></td>
                        <td><a class="btn btn-sm btn-brand" href="<?= site_url('petugas/laporan/' . $item['ticket_number']) ?>">Tindak Lanjut</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3"><?= $pager->links() ?></div>
<?= $this->endSection() ?>
