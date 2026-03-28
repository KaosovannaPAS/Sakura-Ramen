<?php

namespace App\Controllers;

use App\Core\Controller;

class LegalController extends Controller
{
    public function mentions()
    {
        $data = ['title' => 'Mentions Légales — Sakura Ramen'];
        $this->render('mentions', $data);
    }

    public function privacy()
    {
        $data = ['title' => 'Politique de Confidentialité — Sakura Ramen'];
        $this->render('privacy', $data);
    }
}
