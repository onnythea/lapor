<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\ActivityLogModel;
use App\Models\UserModel;

class Auth extends BaseController
{
    private UserModel $users;
    private ActivityLogModel $logs;

    public function __construct()
    {
        $this->users = new UserModel();
        $this->logs  = new ActivityLogModel();
    }

    public function login(): string
    {
        return view('auth/login', ['title' => 'Login']);
    }

    public function attemptLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email    = (string) $this->request->getPost('email');
        $password = (string) $this->request->getPost('password');

        $user = $this->users->findActiveByEmail($email);

        // Verifikasi kredensial dengan konstan-time compare bawaan PHP.
        if ($user === null || ! password_verify($password, $user['password_hash'])) {
            $this->logs->record(null, 'login_failed', 'Percobaan login gagal untuk email: ' . $email);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Email atau password salah.');
        }

        $this->session->set([
            'is_logged_in' => true,
            'role'         => $user['role'],
            'user'         => [
                'id'    => (int) $user['id'],
                'nama'  => $user['nama'],
                'email' => $user['email'],
                'role'  => $user['role'],
            ],
        ]);

        $this->logs->record((int) $user['id'], 'login', 'Login berhasil.');

        // Arahkan sesuai role dengan match expression.
        $target = match ($user['role']) {
            'admin'           => '/petugas/dashboard',
            'petugas'         => '/petugas/dashboard',
            default           => '/pengaduan',
        };

        return redirect()->to($target)->with('message', 'Selamat datang, ' . esc($user['nama']) . '!');
    }

    public function register(): string
    {
        return view('auth/register', ['title' => 'Registrasi']);
    }

    public function attemptRegister()
    {
        // Tentukan jenis pelapor: mahasiswa atau umum.
        $role = $this->request->getPost('role') === 'mahasiswa' ? 'mahasiswa' : 'umum';

        $rules = [
            'nama'     => 'required|min_length[3]|max_length[150]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'pass_confirm' => 'required|matches[password]',
            'nim_nik'  => 'required|alpha_numeric|max_length[30]',
            'no_hp'    => 'permit_empty|numeric|max_length[20]',
        ];

        $messages = [
            'email'        => ['is_unique' => 'Email sudah terdaftar.'],
            'password'     => ['min_length' => 'Password minimal 8 karakter.'],
            'pass_confirm' => ['matches' => 'Konfirmasi password tidak cocok.'],
            'nim_nik'      => ['required' => 'NIM (mahasiswa) atau NIK (umum) wajib diisi.'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            $userId = $this->users->insert([
                'nama'           => $this->request->getPost('nama'),
                'email'          => $this->request->getPost('email'),
                'password_hash'  => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
                'role'           => $role,
                'nim_nik'        => $this->request->getPost('nim_nik'),
                'fakultas_prodi' => $role === 'mahasiswa' ? $this->request->getPost('fakultas_prodi') : null,
                'no_hp'          => $this->request->getPost('no_hp'),
                'is_active'      => 1,
            ], true);
        } catch (\Throwable $e) {
            log_message('error', 'Registrasi gagal: {msg}', ['msg' => $e->getMessage()]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi.');
        }

        $this->logs->record((int) $userId, 'register', 'Registrasi akun baru (' . $role . ').');

        return redirect()->to('/auth/login')
            ->with('message', 'Registrasi berhasil. Silakan login.');
    }

    public function logout()
    {
        $this->logs->record($this->currentUserId(), 'logout', 'Logout dari sistem.');
        $this->session->destroy();

        return redirect()->to('/auth/login')->with('message', 'Anda telah keluar.');
    }
}
