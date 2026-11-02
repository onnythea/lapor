<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="h4 mb-3">Log Aktivitas Sistem (Audit Trail)</h1>
<div class="card">
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Waktu</th><th>Pengguna</th><th>Aksi</th><th>Keterangan</th><th>IP</th></tr></thead>
            <tbody>
                <?php if ($logs === []): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada aktivitas.</td></tr>
                <?php endif; ?>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td class="small text-muted"><?= esc($log['created_at']) ?></td>
                        <td><?= esc($log['user_name'] ?? 'Sistem') ?>
                            <?php if (! empty($log['user_role'])): ?><span class="badge bg-secondary"><?= esc($log['user_role']) ?></span><?php endif; ?>
                        </td>
                        <td><code><?= esc($log['action']) ?></code></td>
                        <td><?= esc($log['description'] ?? '') ?></td>
                        <td class="small text-muted"><?= esc($log['ip_address'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
