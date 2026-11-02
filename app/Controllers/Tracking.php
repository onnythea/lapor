<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\ComplaintModel;
use App\Models\ResponseModel;

class Tracking extends BaseController
{
    public function index(): string
    {
        return view('tracking/index', ['title' => 'Lacak Pengaduan']);
    }

    public function search()
    {
        $ticket = trim((string) $this->request->getPost('ticket_number'));

        if ($ticket === '') {
            return redirect()->back()->with('error', 'Masukkan nomor tiket.');
        }

        return redirect()->to('/tracking/' . urlencode($ticket));
    }

    public function show(string $ticket): string
    {
        $complaints = new ComplaintModel();
        $complaint  = $complaints->findByTicket($ticket);

        // Sembunyikan identitas pelapor bila anonim / rahasia pada tampilan publik.
        if ($complaint !== null) {
            $isPrivate = (int) $complaint['is_anonymous'] === 1 || (int) $complaint['is_secret'] === 1;
            if ($isPrivate) {
                $complaint['reporter_name']  = 'Anonim';
                $complaint['reporter_email'] = null;
            }
        }

        return view('tracking/result', [
            'title'     => 'Hasil Pelacakan',
            'ticket'    => $ticket,
            'complaint' => $complaint,
            'responses' => $complaint !== null
                ? (new ResponseModel())->forComplaint((int) $complaint['id'])
                : [],
        ]);
    }
}
