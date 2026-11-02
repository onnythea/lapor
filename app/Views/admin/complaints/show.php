<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php [$label, $class] = status_badge($complaint['status']); ?>
<div class="row g-4">
    <div class="col-lg-7">
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
                    <dt class="col-sm-3">Pelapor</dt>
                    <dd class="col-sm-9">
                        <?= (int) $complaint['is_anonymous'] === 1 ? 'Anonim' : esc($complaint['reporter_name']) ?>
                        <?= (int) $complaint['is_secret'] === 1 ? ' <span class="badge bg-danger">Rahasia</span>' : '' ?>
                    </dd>
                    <dt class="col-sm-3">Isi</dt><dd class="col-sm-9" style="white-space:pre-line;"><?= esc($complaint['content']) ?></dd>
                    <?php if (! empty($complaint['attachment'])): ?>
                        <dt class="col-sm-3">Lampiran</dt>
                        <dd class="col-sm-9"><a href="<?= site_url('pengaduan/unduh/' . $complaint['id']) ?>">Unduh berkas</a></dd>
                    <?php endif; ?>
                </dl>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body p-4">
                <h2 class="h6 mb-3">Riwayat Tanggapan</h2>
                <?php if ($responses === []): ?><p class="text-muted">Belum ada tanggapan.</p><?php endif; ?>
                <ul class="timeline">
                    <?php foreach ($responses as $r): ?>
                        <li>
                            <div class="fw-semibold"><?= esc($r['responder_name'] ?? '') ?></div>
                            <div class="text-muted small"><?= esc($r['created_at']) ?></div>
                            <div style="white-space:pre-line;"><?= esc($r['response_text']) ?></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card mb-3">
            <div class="card-body p-4">
                <h2 class="h6 mb-3">Ubah Status</h2>
                <?= form_open('/petugas/laporan/' . $complaint['id'] . '/status', ['class' => 'd-flex gap-2']) ?>
                    <select name="status" class="form-select">
                        <?php foreach (['pending' => 'Terkirim', 'processing' => 'Diproses', 'completed' => 'Selesai', 'rejected' => 'Ditolak'] as $k => $v): ?>
                            <option value="<?= $k ?>" <?= $complaint['status'] === $k ? 'selected' : '' ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-brand">Simpan</button>
                <?= form_close() ?>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body p-4">
                <h2 class="h6 mb-3">Kirim Tanggapan</h2>
                <?= form_open('/petugas/laporan/' . $complaint['id'] . '/tanggapan') ?>
                    <textarea name="response_text" rows="4" class="form-control mb-2" required></textarea>
                    <button class="btn btn-brand w-100">Kirim Tanggapan</button>
                <?= form_close() ?>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <h2 class="h6 mb-3">Disposisi ke Unit</h2>
                <?= form_open('/petugas/laporan/' . $complaint['id'] . '/disposisi', ['class' => 'd-flex gap-2']) ?>
                    <input type="text" name="assigned_unit" class="form-control" placeholder="Nama unit / departemen"
                           value="<?= esc($complaint['assigned_unit'] ?? '') ?>" required>
                    <button class="btn btn-outline-secondary">Simpan</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
