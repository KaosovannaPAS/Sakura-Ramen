<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Middlewares\AuthMiddleware;

class DashboardController extends Controller
{
    public function index()
    {
        AuthMiddleware::check();
        
        $data = [
            'title' => 'Dashboard — Sakura Ramen Admin'
        ];
        
        $this->render('admin/dashboard', $data, 'admin');
    }
}
