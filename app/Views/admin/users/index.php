<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Manajemen User</h1>
    <a href="<?= site_url('/admin/users/baru') ?>" class="btn btn-brand btn-sm"><i class="bi bi-person-plus"></i> Tambah User</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Nama</th><th>Email</th><th>Role</th><th>NIM/NIK</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php foreach ($items as $u): ?>
                    <tr>
                        <td><?= esc($u['nama']) ?></td>
                        <td><?= esc($u['email']) ?></td>
                        <td><span class="badge bg-secondary text-uppercase"><?= esc($u['role']) ?></span></td>
                        <td><?= esc($u['nim_nik'] ?? '-') ?></td>
                        <td class="d-flex gap-1 flex-wrap">
                            <a class="btn btn-sm btn-outline-secondary" href="<?= site_url('admin/users/' . $u['id'] . '/edit') ?>">Edit</a>
                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#reset<?= $u['id'] ?>">Reset</button>
                            <?= form_open('/admin/users/' . $u['id'] . '/hapus', ['onsubmit' => "return confirm('Hapus user ini?')"]) ?>
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            <?= form_close() ?>
                        </td>
                    </tr>
                    <div class="modal fade" id="reset<?= $u['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <?= form_open('/admin/users/' . $u['id'] . '/reset-password', ['class' => 'modal-content']) ?>
                                <div class="modal-header"><h5 class="modal-title">Reset Password: <?= esc($u['nama']) ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
                                <div class="modal-body">
                                    <label class="form-label">Password Baru (min. 8 karakter)</label>
                                    <input type="password" name="password" class="form-control" required minlength="8">
                                </div>
                                <div class="modal-footer"><button class="btn btn-brand">Reset</button></div>
                            <?= form_close() ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3"><?= $pager->links() ?></div>
<?= $this->endSection() ?>
