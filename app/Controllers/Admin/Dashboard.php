<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ComplaintModel;

class Dashboard extends BaseController
{
    public function index(): string
    {
        $complaints = new ComplaintModel();
        $counts     = $complaints->statusCounts();

        $latest = $complaints->withRelations()
            ->orderBy('complaints.created_at', 'DESC')
            ->findAll(10);

        return view('admin/dashboard', [
            'title'  => 'Dashboard Petugas',
            'counts' => $counts,
            'total'  => array_sum($counts),
            'latest' => $latest,
        ]);
    }
}
