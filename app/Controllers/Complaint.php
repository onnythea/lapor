<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\ActivityLogModel;
use App\Models\CategoryModel;
use App\Models\ComplaintModel;
use App\Models\RatingModel;
use App\Models\ResponseModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Files\UploadedFile;

class Complaint extends BaseController
{
    private ComplaintModel $complaints;
    private CategoryModel $categories;
    private ActivityLogModel $logs;

    public function __construct()
    {
        $this->complaints = new ComplaintModel();
        $this->categories = new CategoryModel();
        $this->logs       = new ActivityLogModel();
    }

    /**
     * Histori laporan milik pelapor yang sedang login.
     */
    public function index(): string
    {
        $data = $this->complaints->withRelations()
            ->where('complaints.user_id', $this->currentUserId())
            ->orderBy('complaints.created_at', 'DESC')
            ->paginate(10);

        return view('complaints/index', [
            'title'    => 'Histori Pengaduan Saya',
            'items'    => $data,
            'pager'    => $this->complaints->pager,
        ]);
    }

    public function create(): string
    {
        return view('complaints/create', [
            'title'      => 'Buat Pengaduan / Aspirasi',
            'categories' => $this->categories->findAll(),
        ]);
    }

    public function store()
    {
        $rules = [
            'category_id' => 'required|is_natural_no_zero',
            'title'       => 'required|min_length[5]|max_length[200]',
            'content'     => 'required|min_length[15]',
            'attachment'  => [
                'rules'  => 'permit_empty|max_size[attachment,2048]|ext_in[attachment,jpg,jpeg,png,pdf]|mime_in[attachment,image/jpg,image/jpeg,image/png,application/pdf]',
                'errors' => [
                    'max_size' => 'Ukuran berkas maksimal 2MB.',
                    'ext_in'   => 'Berkas harus berformat JPG, PNG, atau PDF.',
                    'mime_in'  => 'Tipe berkas tidak valid.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $attachmentName = $this->handleUpload($this->request->getFile('attachment'));

        // Tombol "Simpan sebagai draft" vs "Kirim sekarang".
        $status = $this->request->getPost('action') === 'submit' ? 'pending' : 'draft';

        try {
            $ticket = generate_ticket_number();

            $id = $this->complaints->insert([
                'ticket_number' => $ticket,
                'user_id'       => $this->currentUserId(),
                'category_id'   => (int) $this->request->getPost('category_id'),
                'title'         => $this->request->getPost('title'),
                'content'       => $this->request->getPost('content'),
                'attachment'    => $attachmentName,
                'status'        => $status,
                'is_anonymous'  => $this->request->getPost('is_anonymous') ? 1 : 0,
                'is_secret'     => $this->request->getPost('is_secret') ? 1 : 0,
            ], true);
        } catch (\Throwable $e) {
            log_message('error', 'Gagal simpan pengaduan: {msg}', ['msg' => $e->getMessage()]);

            return redirect()->back()->withInput()
                ->with('error', 'Gagal menyimpan pengaduan. Silakan coba lagi.');
        }

        $this->logs->record($this->currentUserId(), 'create_complaint', "Membuat laporan {$ticket} (status: {$status}).");

        return redirect()->to('/pengaduan/detail/' . $ticket)
            ->with('message', "Pengaduan tersimpan dengan nomor tiket {$ticket}.");
    }

    public function show(string $ticket): string
    {
        $complaint = $this->complaints->findByTicket($ticket);

        if ($complaint === null || (int) $complaint['user_id'] !== $this->currentUserId()) {
            throw PageNotFoundException::forPageNotFound('Laporan tidak ditemukan.');
        }

        return view('complaints/show', [
            'title'     => 'Detail Tiket ' . $complaint['ticket_number'],
            'complaint' => $complaint,
            'responses' => (new ResponseModel())->forComplaint((int) $complaint['id']),
            'rating'    => (new RatingModel())->where('complaint_id', $complaint['id'])->first(),
        ]);
    }

    /**
     * Ubah draft menjadi terkirim (Draft -> Terkirim).
     */
    public function submit(int $id)
    {
        $complaint = $this->complaints->find($id);

        if ($complaint === null || (int) $complaint['user_id'] !== $this->currentUserId()) {
            throw PageNotFoundException::forPageNotFound();
        }

        if ($complaint['status'] !== 'draft') {
            return redirect()->back()->with('error', 'Laporan sudah dikirim sebelumnya.');
        }

        $this->complaints->update($id, ['status' => 'pending']);
        $this->logs->record($this->currentUserId(), 'submit_complaint', "Mengirim laporan {$complaint['ticket_number']}.");

        return redirect()->back()->with('message', 'Laporan berhasil dikirim dan menunggu verifikasi.');
    }

    /**
     * Umpan balik (rating + komentar) setelah status Selesai.
     */
    public function rate(int $id)
    {
        $complaint = $this->complaints->find($id);

        if ($complaint === null || (int) $complaint['user_id'] !== $this->currentUserId()) {
            throw PageNotFoundException::forPageNotFound();
        }

        if ($complaint['status'] !== 'completed') {
            return redirect()->back()->with('error', 'Umpan balik hanya untuk laporan yang telah Selesai.');
        }

        $ratings = new RatingModel();
        $payload = [
            'complaint_id' => $id,
            'score'        => (int) $this->request->getPost('score'),
            'feedback'     => $this->request->getPost('feedback'),
        ];

        if (! $ratings->insert($payload)) {
            return redirect()->back()->with('errors', $ratings->errors());
        }

        $this->logs->record($this->currentUserId(), 'rate_complaint', "Memberi rating pada {$complaint['ticket_number']}.");

        return redirect()->back()->with('message', 'Terima kasih atas umpan balik Anda.');
    }

    /**
     * Unduh lampiran dengan kontrol akses (hanya pemilik / petugas / admin).
     */
    public function download(int $id)
    {
        $complaint = $this->complaints->find($id);
        $role      = (string) $this->session->get('role');
        $isOwner   = $complaint !== null && (int) $complaint['user_id'] === $this->currentUserId();
        $isStaff   = in_array($role, ['petugas', 'admin'], true);

        if ($complaint === null || $complaint['attachment'] === null || (! $isOwner && ! $isStaff)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $path = WRITEPATH . 'uploads/complaints/' . $complaint['attachment'];
        if (! is_file($path)) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $this->response->download($path, null);
    }

    /**
     * Simpan berkas unggahan ke direktori privat (di luar public root).
     */
    private function handleUpload(?UploadedFile $file): ?string
    {
        if ($file === null || ! $file->isValid() || $file->hasMoved()) {
            return null;
        }

        $targetDir = WRITEPATH . 'uploads/complaints';
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $newName = $file->getRandomName();
        $file->move($targetDir, $newName);

        return $newName;
    }
}
