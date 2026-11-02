<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body p-4">
                <h1 class="h4 mb-3 text-center text-brand">Masuk ke Akun</h1>
                <?= form_open('/auth/login') ?>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                               value="<?= esc(old('email')) ?>" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-brand w-100">Masuk</button>
                <?= form_close() ?>
                <p class="text-center mt-3 mb-0 small">Belum punya akun?
                    <a href="<?= site_url('/auth/register') ?>">Daftar di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
