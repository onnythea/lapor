<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ActivityLogModel;
use App\Models\CategoryModel;
use App\Models\ComplaintModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class CategoryManager extends BaseController
{
    private CategoryModel $categories;
    private ActivityLogModel $logs;

    public function __construct()
    {
        $this->categories = new CategoryModel();
        $this->logs       = new ActivityLogModel();
    }

    public function index(): string
    {
        return view('admin/categories/index', [
            'title' => 'Manajemen Kategori',
            'items' => $this->categories->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        if (! $this->validate(['name' => 'required|min_length[3]|max_length[120]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->categories->insert([
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ]);

        $this->logs->record($this->currentUserId(), 'create_category', 'Menambah kategori baru.');

        return redirect()->to('/admin/kategori')->with('message', 'Kategori ditambahkan.');
    }

    public function update(int $id)
    {
        $category = $this->categories->find($id);
        if ($category === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (! $this->validate(['name' => 'required|min_length[3]|max_length[120]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->categories->update($id, [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ]);

        $this->logs->record($this->currentUserId(), 'update_category', "Memperbarui kategori #{$id}.");

        return redirect()->to('/admin/kategori')->with('message', 'Kategori diperbarui.');
    }

    public function delete(int $id)
    {
        // Cegah hapus kategori yang masih dipakai laporan.
        $used = (new ComplaintModel())->where('category_id', $id)->withDeleted()->countAllResults() > 0;
        if ($used) {
            return redirect()->back()->with('error', 'Kategori masih digunakan oleh laporan dan tidak dapat dihapus.');
        }

        $this->categories->delete($id);
        $this->logs->record($this->currentUserId(), 'delete_category', "Menghapus kategori #{$id}.");

        return redirect()->to('/admin/kategori')->with('message', 'Kategori dihapus.');
    }
}
