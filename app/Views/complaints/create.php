<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <h1 class="h4 mb-1 text-brand">Formulir Pengaduan / Aspirasi</h1>
                <p class="text-muted small mb-4">Isi laporan Anda dengan jelas. Lampiran opsional (JPG, PNG, atau PDF maks. 2MB).</p>

                <?= form_open_multipart('/pengaduan/simpan') ?>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= (int) $c['id'] ?>" <?= old('category_id') == $c['id'] ? 'selected' : '' ?>>
                                    <?= esc($c['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Laporan <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control"
                               value="<?= esc(old('title')) ?>" maxlength="200" required>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Isi Laporan <span class="text-danger">*</span></label>
                        <textarea name="content" id="content" rows="6" class="form-control" required><?= esc(old('content')) ?></textarea>
                        <div class="form-text">Jelaskan kronologi, lokasi, waktu, dan pihak terkait bila ada.</div>
                    </div>

                    <div class="mb-3">
                        <label for="attachment" class="form-label">Upload Bukti (Foto / Dokumen)</label>
                        <input type="file" name="attachment" id="attachment" class="form-control"
                               accept=".jpg,.jpeg,.png,.pdf">
                    </div>

                    <div class="row g-2 mb-4">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_anonymous" id="is_anonymous" value="1"
                                       <?= old('is_anonymous') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_anonymous">Kirim sebagai <strong>Anonim</strong></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_secret" id="is_secret" value="1"
                                       <?= old('is_secret') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_secret">Tandai <strong>Rahasia</strong></label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" name="action" value="submit" class="btn btn-brand">
                            <i class="bi bi-send"></i> Kirim Sekarang
                        </button>
                        <button type="submit" name="action" value="draft" class="btn btn-outline-secondary">
                            <i class="bi bi-save"></i> Simpan sebagai Draft
                        </button>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
