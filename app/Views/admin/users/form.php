<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
    $isEdit = $user !== null;
    $action = $isEdit ? '/admin/users/' . $user['id'] : '/admin/users';
?>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-4">
                <h1 class="h4 mb-3"><?= esc($title) ?></h1>
                <?= form_open($action) ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="nama">Nama</label>
                            <input type="text" name="nama" id="nama" class="form-control" required
                                   value="<?= esc(old('nama', $user['nama'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required
                                   value="<?= esc(old('email', $user['email'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="role">Role</label>
                            <select name="role" id="role" class="form-select" required>
                                <?php foreach (['admin', 'petugas', 'mahasiswa', 'umum'] as $r): ?>
                                    <option value="<?= $r ?>" <?= old('role', $user['role'] ?? '') === $r ? 'selected' : '' ?>><?= ucfirst($r) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="nim_nik">NIM / NIK</label>
                            <input type="text" name="nim_nik" id="nim_nik" class="form-control"
                                   value="<?= esc(old('nim_nik', $user['nim_nik'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="no_hp">No. HP</label>
                            <input type="text" name="no_hp" id="no_hp" class="form-control"
                                   value="<?= esc(old('no_hp', $user['no_hp'] ?? '')) ?>">
                        </div>
                        <?php if (! $isEdit): ?>
                            <div class="col-md-6">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required minlength="8">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-brand">Simpan</button>
                        <a href="<?= site_url('/admin/users') ?>" class="btn btn-outline-secondary">Batal</a>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
