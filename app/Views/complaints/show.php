<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
    [$statusLabel, $statusClass] = status_badge($complaint['status']);
    $steps = ['draft' => 'Draft', 'pending' => 'Terkirim', 'processing' => 'Diproses', 'completed' => 'Selesai'];
    $order = ['draft' => 0, 'pending' => 1, 'processing' => 2, 'completed' => 3, 'rejected' => 3];
    $current = $order[$complaint['status']] ?? 0;
?>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <span class="text-muted small">Nomor Tiket</span>
                        <h1 class="h4 mb-0 text-brand"><?= esc($complaint['ticket_number']) ?></h1>
                    </div>
                    <span class="badge <?= $statusClass ?> fs-6"><?= $statusLabel ?></span>
                </div>
                <hr>
                <dl class="row mb-0">
                    <dt class="col-sm-3">Judul</dt>
                    <dd class="col-sm-9"><?= esc($complaint['title']) ?></dd>
                    <dt class="col-sm-3">Kategori</dt>
                    <dd class="col-sm-9"><?= esc($complaint['category_name']) ?></dd>
                    <dt class="col-sm-3">Tanggal</dt>
                    <dd class="col-sm-9"><?= esc($complaint['created_at']) ?></dd>
                    <dt class="col-sm-3">Isi Laporan</dt>
                    <dd class="col-sm-9" style="white-space:pre-line;"><?= esc($complaint['content']) ?></dd>
                    <?php if (! empty($complaint['assigned_unit'])): ?>
                        <dt class="col-sm-3">Disposisi</dt>
                        <dd class="col-sm-9"><?= esc($complaint['assigned_unit']) ?></dd>
                    <?php endif; ?>
                    <?php if (! empty($complaint['attachment'])): ?>
                        <dt class="col-sm-3">Lampiran</dt>
                        <dd class="col-sm-9">
                            <a href="<?= site_url('pengaduan/unduh/' . $complaint['id']) ?>"><i class="bi bi-paperclip"></i> Unduh berkas</a>
                        </dd>
                    <?php endif; ?>
                </dl>

                <?php if ($complaint['status'] === 'draft'): ?>
                    <?= form_open('/pengaduan/' . $complaint['id'] . '/kirim', ['class' => 'mt-3']) ?>
                        <button class="btn btn-brand"><i class="bi bi-send"></i> Kirim Laporan Ini</button>
                    <?= form_close() ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Tanggapan Petugas</h2>
                <?php if ($responses === []): ?>
                    <p class="text-muted mb-0">Belum ada tanggapan.</p>
                <?php else: ?>
                    <ul class="timeline">
                        <?php foreach ($responses as $r): ?>
                            <li>
                                <div class="fw-semibold"><?= esc($r['responder_name'] ?? 'Petugas') ?>
                                    <span class="badge bg-light text-dark ms-1"><?= esc($r['responder_role'] ?? '') ?></span>
                                </div>
                                <div class="text-muted small"><?= esc($r['created_at']) ?></div>
                                <div style="white-space:pre-line;"><?= esc($r['response_text']) ?></div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body p-4">
                <h2 class="h6 mb-3">Progres Penanganan</h2>
                <ul class="timeline">
                    <?php foreach ($steps as $key => $label): ?>
                        <?php $done = $order[$key] <= $current && $complaint['status'] !== 'rejected'; ?>
                        <li class="<?= $done ? '' : 'text-muted' ?>">
                            <?= $label ?> <?= $done ? '<i class="bi bi-check-circle-fill text-success"></i>' : '' ?>
                        </li>
                    <?php endforeach; ?>
                    <?php if ($complaint['status'] === 'rejected'): ?>
                        <li class="text-danger">Ditolak <i class="bi bi-x-circle-fill"></i></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <?php if ($complaint['status'] === 'completed'): ?>
            <div class="card">
                <div class="card-body p-4">
                    <h2 class="h6 mb-3">Umpan Balik</h2>
                    <?php if ($rating !== null): ?>
                        <p class="mb-1">Rating: <strong><?= (int) $rating['score'] ?>/5</strong></p>
                        <p class="text-muted mb-0"><?= esc($rating['feedback'] ?? '') ?></p>
                    <?php else: ?>
                        <?= form_open('/pengaduan/' . $complaint['id'] . '/rating') ?>
                            <div class="mb-2">
                                <label for="score" class="form-label">Skor Kepuasan</label>
                                <select name="score" id="score" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="feedback" class="form-label">Komentar</label>
                                <textarea name="feedback" id="feedback" rows="3" class="form-control"></textarea>
                            </div>
                            <button class="btn btn-brand w-100">Kirim Umpan Balik</button>
                        <?= form_close() ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
