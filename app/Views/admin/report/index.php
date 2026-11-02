<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="h4 mb-3">Cetak &amp; Ekspor Laporan</h1>

<div class="card mb-3">
    <div class="card-body">
        <form method="get" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label" for="start">Dari Tanggal</label>
                <input type="date" name="start" id="start" class="form-control" value="<?= esc($filters['start']) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label" for="end">Sampai Tanggal</label>
                <input type="date" name="end" id="end" class="form-control" value="<?= esc($filters['end']) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label" for="status">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Semua</option>
                    <?php foreach (['pending' => 'Terkirim', 'processing' => 'Diproses', 'completed' => 'Selesai', 'rejected' => 'Ditolak'] as $k => $v): ?>
                        <option value="<?= $k ?>" <?= $filters['status'] === $k ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="category_id">Kategori</label>
                <select name="category_id" id="category_id" class="form-select">
                    <option value="">Semua</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= (string) $filters['category_id'] === (string) $c['id'] ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 d-flex gap-2 mt-2">
                <button class="btn btn-brand">Terapkan Filter</button>
                <a class="btn btn-outline-danger" href="<?= site_url('admin/rekap/pdf?' . http_build_query($filters)) ?>"><i class="bi bi-file-pdf"></i> Ekspor PDF</a>
                <a class="btn btn-outline-success" href="<?= site_url('admin/rekap/excel?' . http_build_query($filters)) ?>"><i class="bi bi-file-excel"></i> Ekspor Excel</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>#</th><th>Tiket</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Tanggal</th></tr></thead>
            <tbody>
                <?php foreach ($items as $i => $item): ?>
                    <?php [$label] = status_badge($item['status']); ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><code><?= esc($item['ticket_number']) ?></code></td>
                        <td><?= esc($item['title']) ?></td>
                        <td><?= esc($item['category_name']) ?></td>
                        <td><?= $label ?></td>
                        <td class="small text-muted"><?= esc($item['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
