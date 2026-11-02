<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ActivityLogModel;

class ActivityLog extends BaseController
{
    public function index(): string
    {
        return view('admin/activity_log/index', [
            'title' => 'Log Aktivitas Sistem',
            'logs'  => (new ActivityLogModel())->withUser(),
        ]);
    }
}
