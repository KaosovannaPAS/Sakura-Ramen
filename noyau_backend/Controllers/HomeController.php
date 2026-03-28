<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Sakura Ramen — Comptoir à Ramen Artisanal',
            'meta_description' => 'Découvrez Sakura Ramen à Vannes, un comptoir à ramen premium offrant des bouillons umami et une expérience culinaire japonaise immersive.'
        ];
        
        $this->render('home', $data);
    }
}
