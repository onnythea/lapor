<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="h4 mb-3">Manajemen Kategori</h1>
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body p-4">
                <h2 class="h6 mb-3">Tambah Kategori</h2>
                <?= form_open('/admin/kategori') ?>
                    <div class="mb-2">
                        <label class="form-label" for="name">Nama Kategori</label>
                        <input type="text" name="name" id="name" class="form-control" required value="<?= esc(old('name')) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="description">Deskripsi</label>
                        <textarea name="description" id="description" rows="3" class="form-control"><?= esc(old('description')) ?></textarea>
                    </div>
                    <button class="btn btn-brand w-100">Tambah</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light"><tr><th>Nama</th><th>Slug</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <?php foreach ($items as $c): ?>
                            <tr>
                                <td><?= esc($c['name']) ?><div class="small text-muted"><?= esc($c['description']) ?></div></td>
                                <td><code><?= esc($c['slug']) ?></code></td>
                                <td class="d-flex gap-1">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#edit<?= $c['id'] ?>">Edit</button>
                                    <?= form_open('/admin/kategori/' . $c['id'] . '/hapus', ['onsubmit' => "return confirm('Hapus kategori?')"]) ?>
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    <?= form_close() ?>
                                </td>
                            </tr>
                            <div class="modal fade" id="edit<?= $c['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <?= form_open('/admin/kategori/' . $c['id'], ['class' => 'modal-content']) ?>
                                        <div class="modal-header"><h5 class="modal-title">Edit Kategori</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
                                        <div class="modal-body">
                                            <label class="form-label">Nama</label>
                                            <input type="text" name="name" class="form-control mb-2" value="<?= esc($c['name']) ?>" required>
                                            <label class="form-label">Deskripsi</label>
                                            <textarea name="description" rows="3" class="form-control"><?= esc($c['description']) ?></textarea>
                                        </div>
                                        <div class="modal-footer"><button class="btn btn-brand">Simpan</button></div>
                                    <?= form_close() ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
