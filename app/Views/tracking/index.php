<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-4 text-center">
                <i class="bi bi-search text-brand" style="font-size:2.5rem;"></i>
                <h1 class="h4 mt-2 mb-3">Lacak Status Pengaduan</h1>
                <?= form_open('/tracking/cari', ['class' => 'd-flex gap-2']) ?>
                    <input type="text" name="ticket_number" class="form-control" placeholder="Contoh: TKT-20260922-0421" required>
                    <button class="btn btn-brand">Lacak</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
