<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ActivityLogModel;
use App\Models\ComplaintModel;
use App\Models\ResponseModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class ComplaintManager extends BaseController
{
    private ComplaintModel $complaints;
    private ResponseModel $responses;
    private ActivityLogModel $logs;

    public function __construct()
    {
        $this->complaints = new ComplaintModel();
        $this->responses  = new ResponseModel();
        $this->logs       = new ActivityLogModel();
    }

    public function index(): string
    {
        $status   = $this->request->getGet('status');
        $keyword  = trim((string) $this->request->getGet('q'));

        $builder = $this->complaints->withRelations();

        if (in_array($status, ['pending', 'processing', 'completed', 'rejected'], true)) {
            $builder->where('complaints.status', $status);
        }

        if ($keyword !== '') {
            $builder->groupStart()
                ->like('complaints.ticket_number', $keyword)
                ->orLike('complaints.title', $keyword)
                ->groupEnd();
        }

        $items = $builder->orderBy('complaints.created_at', 'DESC')->paginate(15);

        return view('admin/complaints/index', [
            'title'   => 'Kelola Laporan Masuk',
            'items'   => $items,
            'pager'   => $this->complaints->pager,
            'status'  => $status,
            'keyword' => $keyword,
        ]);
    }

    public function show(string $ticket): string
    {
        $complaint = $this->complaints->findByTicket($ticket);

        if ($complaint === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('admin/complaints/show', [
            'title'     => 'Tindak Lanjut ' . $complaint['ticket_number'],
            'complaint' => $complaint,
            'responses' => $this->responses->forComplaint((int) $complaint['id']),
        ]);
    }

    public function updateStatus(int $id)
    {
        $complaint = $this->complaints->find($id);
        if ($complaint === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $status = (string) $this->request->getPost('status');
        if (! in_array($status, ['pending', 'processing', 'completed', 'rejected'], true)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $this->complaints->update($id, ['status' => $status]);
        [$label] = status_badge($status);

        $this->logs->record(
            $this->currentUserId(),
            'update_status',
            "Ubah status {$complaint['ticket_number']} menjadi {$label}."
        );

        return redirect()->back()->with('message', "Status laporan diubah menjadi {$label}.");
    }

    public function respond(int $id)
    {
        $complaint = $this->complaints->find($id);
        if ($complaint === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (! $this->validate(['response_text' => 'required|min_length[3]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $ok = $this->responses->insert([
            'complaint_id'  => $id,
            'user_id'       => $this->currentUserId(),
            'response_text' => $this->request->getPost('response_text'),
        ]);

        if (! $ok) {
            return redirect()->back()->with('errors', $this->responses->errors());
        }

        // Bila masih pending, otomatis naikkan ke processing saat petugas menanggapi.
        if ($complaint['status'] === 'pending') {
            $this->complaints->update($id, ['status' => 'processing']);
        }

        $this->logs->record($this->currentUserId(), 'respond', "Menanggapi laporan {$complaint['ticket_number']}.");

        return redirect()->back()->with('message', 'Tanggapan berhasil dikirim.');
    }

    public function dispose(int $id)
    {
        $complaint = $this->complaints->find($id);
        if ($complaint === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $unit = trim((string) $this->request->getPost('assigned_unit'));
        if ($unit === '') {
            return redirect()->back()->with('error', 'Unit tujuan disposisi wajib diisi.');
        }

        $this->complaints->update($id, [
            'assigned_unit' => $unit,
            'status'        => $complaint['status'] === 'pending' ? 'processing' : $complaint['status'],
        ]);

        $this->logs->record($this->currentUserId(), 'dispose', "Disposisi {$complaint['ticket_number']} ke {$unit}.");

        return redirect()->back()->with('message', "Laporan didisposisikan ke {$unit}.");
    }
}
