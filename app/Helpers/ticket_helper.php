<?php

declare(strict_types=1);

use App\Models\ComplaintModel;

if (! function_exists('generate_ticket_number')) {
    /**
     * Membuat nomor tiket unik dengan format TKT-YYYYMMDD-XXXX.
     * Dijamin unik terhadap tabel complaints.
     */
    function generate_ticket_number(): string
    {
        $model  = new ComplaintModel();
        $prefix = 'TKT-' . date('Ymd') . '-';

        do {
            $random = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $ticket = $prefix . $random;

            $exists = $model->where('ticket_number', $ticket)
                ->withDeleted()
                ->countAllResults() > 0;
        } while ($exists);

        return $ticket;
    }
}

if (! function_exists('status_badge')) {
    /**
     * Kembalikan pasangan [label, kelas bootstrap] untuk sebuah status.
     *
     * @return array{0: string, 1: string}
     */
    function status_badge(string $status): array
    {
        return match ($status) {
            'draft'      => ['Draft', 'bg-secondary'],
            'pending'    => ['Terkirim', 'bg-info text-dark'],
            'processing' => ['Diproses', 'bg-warning text-dark'],
            'completed'  => ['Selesai', 'bg-success'],
            'rejected'   => ['Ditolak', 'bg-danger'],
            default      => ['Tidak diketahui', 'bg-light text-dark'],
        };
    }
}
