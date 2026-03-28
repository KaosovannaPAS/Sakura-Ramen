<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\MenuCategory;
use App\Models\MenuItem;

class OrderController extends Controller
{
    public function index()
    {
        $menuData = [];
        
        try {
            $categoryModel = new MenuCategory();
            $itemModel = new MenuItem();
            $categories = $categoryModel->getAllActive();

            foreach ($categories as $category) {
                $menuData[] = [
                    'category' => $category,
                    'items' => $itemModel->getByCategory($category['id'])
                ];
            }
        } catch (\Exception $e) {}

        if (empty($menuData)) {
            $menuData = [
                [
                    'category' => ['name' => 'Entrées', 'slug' => 'entrees'],
                    'items' => [
                        ['id' => 1, 'name' => 'Gyoza Maison (x5)', 'description' => 'Raviolis grillés au porc et gingembre.', 'price' => 7.50, 'image' => '/img/item_gyoza.png'],
                        ['id' => 2, 'name' => 'Edamame au Fleur de Sel', 'description' => 'Fèves de soja à la vapeur.', 'price' => 5.00, 'image' => '/img/item_edamame.png'],
                        ['id' => 3, 'name' => 'Karaage Sakura', 'description' => 'Poulet frit japonais, mayo yuzu.', 'price' => 8.50, 'image' => '/img/item_karaage.png']
                    ]
                ],
                [
                    'category' => ['name' => 'Ramen Signature', 'slug' => 'ramen'],
                    'items' => [
                        ['id' => 4, 'name' => 'Shio Ramen Sakura', 'description' => 'Bouillon clair, chashu fondant, ajitama.', 'price' => 14.50, 'image' => '/img/item_shio_ramen.png'],
                        ['id' => 5, 'name' => 'Tonkotsu Classic', 'description' => 'Bouillon crémeux, ail noir, nori.', 'price' => 16.00, 'image' => '/img/item_tonkotsu.png'],
                        ['id' => 6, 'name' => 'Tantanmen Épicé', 'description' => 'Porc haché, huile de piment, sésame.', 'price' => 15.50, 'image' => '/img/item_tantanmen.png']
                    ]
                ],
                [
                    'category' => ['name' => 'Desserts & Boissons', 'slug' => 'desserts'],
                    'items' => [
                        ['id' => 7, 'name' => 'Mochi Glacé (x2)', 'description' => 'Matcha, Sésame Noir ou Fleur de Cerisier.', 'price' => 6.50, 'image' => '/img/item_mochi.png'],
                        ['id' => 8, 'name' => 'Sake Premium', 'description' => 'Servi froid ou chaud, sélection artisanale.', 'price' => 9.00, 'image' => '/img/item_sake.png'],
                        ['id' => 9, 'name' => 'Dorayaki', 'description' => 'Pancake japonais au haricot rouge.', 'price' => 7.00, 'image' => '/img/item_dorayaki.png']
                    ]
                ]
            ];
        }

        $data = [
            'title' => 'Commander en Ligne — Sakura Ramen',
            'meta_description' => 'Commandez vos ramen artisanaux en ligne pour retrait au comptoir chez Sakura Ramen à Vannes.',
            'menu' => $menuData
        ];

        $this->render('order', $data);
    }
}
