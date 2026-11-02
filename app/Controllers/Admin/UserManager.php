<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ActivityLogModel;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class UserManager extends BaseController
{
    private UserModel $users;
    private ActivityLogModel $logs;

    public function __construct()
    {
        $this->users = new UserModel();
        $this->logs  = new ActivityLogModel();
    }

    public function index(): string
    {
        return view('admin/users/index', [
            'title' => 'Manajemen User',
            'items' => $this->users->orderBy('created_at', 'DESC')->paginate(15),
            'pager' => $this->users->pager,
        ]);
    }

    public function create(): string
    {
        return view('admin/users/form', ['title' => 'Tambah User', 'user' => null]);
    }

    public function store()
    {
        $rules = [
            'nama'     => 'required|min_length[3]|max_length[150]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'role'     => 'required|in_list[admin,petugas,mahasiswa,umum]',
            'password' => 'required|min_length[8]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $id = $this->users->insert([
            'nama'          => $this->request->getPost('nama'),
            'email'         => $this->request->getPost('email'),
            'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'          => $this->request->getPost('role'),
            'nim_nik'       => $this->request->getPost('nim_nik'),
            'no_hp'         => $this->request->getPost('no_hp'),
            'is_active'     => 1,
        ], true);

        $this->logs->record($this->currentUserId(), 'create_user', "Menambah user #{$id}.");

        return redirect()->to('/admin/users')->with('message', 'User berhasil ditambahkan.');
    }

    public function edit(int $id): string
    {
        $user = $this->users->find($id);
        if ($user === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('admin/users/form', ['title' => 'Edit User', 'user' => $user]);
    }

    public function update(int $id)
    {
        $user = $this->users->find($id);
        if ($user === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'nama'  => 'required|min_length[3]|max_length[150]',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'role'  => 'required|in_list[admin,petugas,mahasiswa,umum]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->users->update($id, [
            'nama'    => $this->request->getPost('nama'),
            'email'   => $this->request->getPost('email'),
            'role'    => $this->request->getPost('role'),
            'nim_nik' => $this->request->getPost('nim_nik'),
            'no_hp'   => $this->request->getPost('no_hp'),
        ]);

        $this->logs->record($this->currentUserId(), 'update_user', "Memperbarui user #{$id}.");

        return redirect()->to('/admin/users')->with('message', 'Data user diperbarui.');
    }

    public function resetPassword(int $id)
    {
        $user = $this->users->find($id);
        if ($user === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (! $this->validate(['password' => 'required|min_length[8]'])) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $this->users->setPassword($id, (string) $this->request->getPost('password'));
        $this->logs->record($this->currentUserId(), 'reset_password', "Reset password user #{$id}.");

        return redirect()->back()->with('message', 'Password user berhasil direset.');
    }

    public function delete(int $id)
    {
        if ($id === $this->currentUserId()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $this->users->delete($id); // soft delete
        $this->logs->record($this->currentUserId(), 'delete_user', "Menghapus user #{$id}.");

        return redirect()->to('/admin/users')->with('message', 'User dihapus.');
    }
}
