<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color:#222; }
        h2 { margin: 0 0 4px; }
        .meta { color:#555; font-size: 10px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 5px 6px; text-align: left; }
        th { background: #0d4d92; color: #fff; }
        tr:nth-child(even) td { background: #f2f5fa; }
    </style>
</head>
<body>
    <h2>Rekap Laporan Pengaduan</h2>
    <div class="meta">
        Dicetak: <?= date('d-m-Y H:i') ?>
        <?php if (! empty($filters['start']) || ! empty($filters['end'])): ?>
            | Periode: <?= esc($filters['start'] ?: '...') ?> s/d <?= esc($filters['end'] ?: '...') ?>
        <?php endif; ?>
    </div>
    <table>
        <thead>
            <tr><th>No</th><th>Nomor Tiket</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Pelapor</th><th>Tanggal</th></tr>
        </thead>
        <tbody>
            <?php foreach ($items as $i => $item): ?>
                <?php [$label] = status_badge($item['status']); ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= esc($item['ticket_number']) ?></td>
                    <td><?= esc($item['title']) ?></td>
                    <td><?= esc($item['category_name']) ?></td>
                    <td><?= $label ?></td>
                    <td><?= (int) $item['is_anonymous'] === 1 ? 'Anonim' : esc($item['reporter_name']) ?></td>
                    <td><?= esc($item['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if ($items === []): ?>
                <tr><td colspan="7" style="text-align:center;">Tidak ada data.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
