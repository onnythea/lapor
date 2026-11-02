<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\ComplaintModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Report extends BaseController
{
    private ComplaintModel $complaints;

    public function __construct()
    {
        $this->complaints = new ComplaintModel();
    }

    public function index(): string
    {
        return view('admin/report/index', [
            'title'      => 'Cetak & Ekspor Laporan',
            'categories' => (new CategoryModel())->findAll(),
            'items'      => $this->query(),
            'filters'    => $this->filters(),
        ]);
    }

    /**
     * @return array<string, string|null>
     */
    private function filters(): array
    {
        return [
            'start'       => $this->request->getGet('start'),
            'end'         => $this->request->getGet('end'),
            'status'      => $this->request->getGet('status'),
            'category_id' => $this->request->getGet('category_id'),
        ];
    }

    /**
     * Query laporan dengan filter tanggal, status, dan kategori.
     *
     * @return list<array<string, mixed>>
     */
    private function query(): array
    {
        $f       = $this->filters();
        $builder = $this->complaints->withRelations();

        if (! empty($f['start'])) {
            $builder->where('complaints.created_at >=', $f['start'] . ' 00:00:00');
        }
        if (! empty($f['end'])) {
            $builder->where('complaints.created_at <=', $f['end'] . ' 23:59:59');
        }
        if (in_array($f['status'], ['pending', 'processing', 'completed', 'rejected'], true)) {
            $builder->where('complaints.status', $f['status']);
        }
        if (! empty($f['category_id'])) {
            $builder->where('complaints.category_id', (int) $f['category_id']);
        }

        return $builder->orderBy('complaints.created_at', 'DESC')->findAll();
    }

    public function exportPdf(): void
    {
        $items = $this->query();

        $html = view('admin/report/pdf', [
            'items'   => $items,
            'filters' => $this->filters(),
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', false);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('rekap-pengaduan-' . date('Ymd-His') . '.pdf', ['Attachment' => true]);
        exit;
    }

    public function exportExcel(): void
    {
        $items = $this->query();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Pengaduan');

        $headers = ['No', 'Nomor Tiket', 'Judul', 'Kategori', 'Status', 'Pelapor', 'Tanggal'];
        $sheet->fromArray($headers, null, 'A1');

        $row = 2;
        foreach ($items as $i => $item) {
            [$label] = status_badge($item['status']);
            $sheet->fromArray([
                $i + 1,
                $item['ticket_number'],
                $item['title'],
                $item['category_name'],
                $label,
                (int) $item['is_anonymous'] === 1 ? 'Anonim' : $item['reporter_name'],
                $item['created_at'],
            ], null, 'A' . $row);
            $row++;
        }

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'rekap-pengaduan-' . date('Ymd-His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        (new Xlsx($spreadsheet))->save('php://output');
        exit;
    }
}
