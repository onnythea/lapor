<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body p-4">
                <h1 class="h4 mb-3 text-center text-brand">Registrasi Pelapor</h1>
                <?= form_open('/auth/register') ?>
                    <div class="mb-3">
                        <label class="form-label d-block">Jenis Pelapor</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="role" id="role_mhs" value="mahasiswa"
                                   <?= old('role') !== 'umum' ? 'checked' : '' ?> onchange="toggleMhs(true)">
                            <label class="form-check-label" for="role_mhs">Mahasiswa</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="role" id="role_umum" value="umum"
                                   <?= old('role') === 'umum' ? 'checked' : '' ?> onchange="toggleMhs(false)">
                            <label class="form-check-label" for="role_umum">Masyarakat Umum</label>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" class="form-control" value="<?= esc(old('nama')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="<?= esc(old('email')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="nim_nik" class="form-label"><span id="idLabel">NIM</span> / NIK</label>
                            <input type="text" name="nim_nik" id="nim_nik" class="form-control" value="<?= esc(old('nim_nik')) ?>" required>
                        </div>
                        <div class="col-md-6" id="prodiWrap">
                            <label for="fakultas_prodi" class="form-label">Fakultas / Prodi</label>
                            <input type="text" name="fakultas_prodi" id="fakultas_prodi" class="form-control" value="<?= esc(old('fakultas_prodi')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="no_hp" class="form-label">No. HP</label>
                            <input type="text" name="no_hp" id="no_hp" class="form-control" value="<?= esc(old('no_hp')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password (min. 8 karakter)</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="pass_confirm" class="form-label">Konfirmasi Password</label>
                            <input type="password" name="pass_confirm" id="pass_confirm" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-brand w-100 mt-4">Daftar</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<script>
    function toggleMhs(isMhs) {
        document.getElementById('prodiWrap').style.display = isMhs ? '' : 'none';
        document.getElementById('idLabel').textContent = isMhs ? 'NIM' : 'NIK';
    }
    toggleMhs(document.getElementById('role_mhs').checked);
</script>
<?= $this->endSection() ?>
