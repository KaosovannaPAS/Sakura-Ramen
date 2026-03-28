<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\TeamMember;

class PageController extends Controller
{
    public function restaurant()
    {
        $data = [
            'title' => 'Le Restaurant — Sakura Ramen',
            'meta_description' => 'Découvrez l\'univers de Sakura Ramen, notre cuisine ouverte et l\'engagement pour des produits de qualité.'
        ];
        $this->render('restaurant', $data);
    }

    public function team()
    {
        if (empty($members)) {
            $members = [
                [
                    'name' => 'Sarah Valloton',
                    'role' => 'Fondatrice & Visionnaire',
                    'bio' => 'Passionnée par la culture nippone depuis son plus jeune âge, Sarah a fondé Sakura Ramen avec une vision simple : apporter l\'authenticité des ruelles de Tokyo au cœur de Vannes.',
                    'image' => '/img/team_founder.png'
                ],
                [
                    'name' => 'Chef Kenzo Sushi',
                    'role' => 'Chef de Cuisine',
                    'bio' => 'Originaire d\'Osaka, le Chef Kenzo apporte 20 ans d\'expérience dans l\'art du bouillon. Maître du Shio et du Tonkotsu, il refuse tout compromis sur la qualité.',
                    'image' => '/img/team_chef.png'
                ],
                [
                    'name' => 'Léa Durand',
                    'role' => 'Chef de Salle',
                    'bio' => 'Léa assure la fluidité du service avec une élégance naturelle. Experte en sakés, elle saura vous conseiller l\'accord parfait pour sublimer votre Ramen.',
                    'image' => '/img/team_lea.jpg'
                ],
                [
                    'name' => 'Nicolas Petit',
                    'role' => 'Chef de Salle',
                    'bio' => 'Véritable chef d\'orchestre, Nicolas veille au confort de chaque client. Son sens du détail et son accueil chaleureux font de chaque visite une expérience unique.',
                    'image' => '/img/team_nicolas.jpg'
                ],
                [
                    'name' => 'Emma Leroy',
                    'role' => 'Serveuse Sakura',
                    'bio' => 'Emma incarne l\'esprit de service à la japonaise : discrétion, efficacité et attention constante. Toujours souriante, elle est aux petits soins pour vous.',
                    'image' => '/img/team_emma.jpg'
                ],
                [
                    'name' => 'Thomas Bernard',
                    'role' => 'Serveur Sakura',
                    'bio' => 'Thomas partage sa passion pour la gastronomie nippone avec enthousiasme. Rapide et attentif, il s\'assure que votre voyage culinaire soit sans fausse note.',
                    'image' => '/img/team_thomas.jpg'
                ]
            ];
        }

        $data = [
            'title' => 'L\'Équipe — Sakura Ramen',
            'members' => $members
        ];
        $this->render('team', $data);
    }
}
