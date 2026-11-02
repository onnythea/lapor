<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="h4 mb-3">Dashboard Analitik</h1>
<div class="row g-3 mb-4">
    <?php
        $cards = [
            ['Total Laporan', $total, 'bg-brand text-white', 'bi-inboxes'],
            ['Terkirim', $counts['pending'], 'bg-info text-dark', 'bi-send'],
            ['Diproses', $counts['processing'], 'bg-warning text-dark', 'bi-gear'],
            ['Selesai', $counts['completed'], 'bg-success text-white', 'bi-check2-circle'],
            ['Ditolak', $counts['rejected'], 'bg-danger text-white', 'bi-x-circle'],
        ];
    ?>
    <?php foreach ($cards as [$labelText, $value, $cls, $icon]): ?>
        <div class="col-6 col-md">
            <div class="card <?= $cls ?> h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small opacity-75"><?= $labelText ?></div>
                            <div class="h3 mb-0 fw-bold"><?= (int) $value ?></div>
                        </div>
                        <i class="bi <?= $icon ?> fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="card">
    <div class="card-header bg-white fw-semibold">Laporan Terbaru</div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Tiket</th><th>Judul</th><th>Kategori</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($latest as $item): ?>
                    <?php [$label, $class] = status_badge($item['status']); ?>
                    <tr>
                        <td><code><?= esc($item['ticket_number']) ?></code></td>
                        <td><?= esc($item['title']) ?></td>
                        <td><?= esc($item['category_name']) ?></td>
                        <td><span class="badge <?= $class ?>"><?= $label ?></span></td>
                        <td><a class="btn btn-sm btn-outline-secondary" href="<?= site_url('petugas/laporan/' . $item['ticket_number']) ?>">Tindak Lanjut</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
