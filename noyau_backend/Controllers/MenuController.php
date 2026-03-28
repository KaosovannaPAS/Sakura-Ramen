<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\MenuCategory;
use App\Models\MenuItem;

class MenuController extends Controller
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
                    'category' => ['name' => 'Entrées (Otsumami)', 'description' => 'Pour commencer votre voyage', 'slug' => 'entrees', 'image' => '/img/menu_entries.png'],
                    'items' => [
                        ['name' => 'Gyoza Maison (x5)', 'description' => 'Raviolis grillés au porc et gingembre.', 'price' => 7.50, 'badges' => json_encode([]), 'image' => '/img/item_gyoza.png'],
                        ['name' => 'Edamame au Fleur de Sel', 'description' => 'Fèves de soja à la vapeur.', 'price' => 5.00, 'badges' => json_encode(['veggie']), 'image' => '/img/item_edamame.png'],
                        ['name' => 'Karaage Sakura', 'description' => 'Poulet frit à la japonaise, mayonnaise au yuzu.', 'price' => 8.50, 'badges' => json_encode(['signature']), 'image' => '/img/item_karaage.png']
                    ]
                ],
                [
                    'category' => ['name' => 'Ramen Signature', 'description' => 'Nos bouillons premium mijotés 12h', 'slug' => 'ramen', 'image' => '/img/menu_ramen.png'],
                    'items' => [
                        ['name' => 'Shio Ramen Sakura', 'description' => 'Bouillon clair au sel de mer, chashu de porc fondant, ajitama et bambou.', 'price' => 14.50, 'badges' => json_encode(['signature']), 'image' => '/img/item_shio_ramen.png'],
                        ['name' => 'Tonkotsu Classic', 'description' => 'Bouillon d\'os de porc crémeux, ail noir, champignons noirs et nori.', 'price' => 16.00, 'badges' => json_encode(['signature']), 'image' => '/img/item_tonkotsu.png'],
                        ['name' => 'Tantanmen Épicé', 'description' => 'Porc haché épicé, huile de piment, pâte de sésame et pak choï.', 'price' => 15.50, 'badges' => json_encode(['spicy']), 'image' => '/img/item_tantanmen.png']
                    ]
                ],
                [
                    'category' => ['name' => 'Drinks & Desserts', 'description' => 'Une note de douceur sucrée', 'slug' => 'desserts', 'image' => '/img/menu_drinks.png'],
                    'items' => [
                        ['name' => 'Mochi Glacé (x2)', 'description' => 'Parfums : Matcha, Sésame Noir ou Fleur de Cerisier.', 'price' => 6.50, 'badges' => json_encode(['veggie']), 'image' => '/img/item_mochi.png'],
                        ['name' => 'Sake Premium', 'description' => 'Servi froid ou chaud, sélection artisanale.', 'price' => 9.00, 'badges' => json_encode(['signature']), 'image' => '/img/item_sake.png'],
                        ['name' => 'Dorayaki au Haricot Rouge', 'description' => 'Pancake japonais traditionnel.', 'price' => 7.00, 'badges' => json_encode([]) , 'image' => '/img/item_dorayaki.png']
                    ]
                ]
            ];
        }

        $data = [
            'title' => 'Notre Carte — Sakura Ramen',
            'menu' => $menuData
        ];

        $this->render('menu', $data);
    }
}
