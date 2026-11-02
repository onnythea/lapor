<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\CategoryModel;

class Home extends BaseController
{
    public function index(): string
    {
        return view('home', [
            'title'      => 'Beranda - LAPOR Aspirasi',
            'categories' => (new CategoryModel())->findAll(),
        ]);
    }
}
