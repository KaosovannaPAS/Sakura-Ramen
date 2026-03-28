<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\MenuItem;
use App\Models\MenuCategory;
use App\Middlewares\AuthMiddleware;
use App\Services\MediaService;

class MenuController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::check();
    }

    public function index()
    {
        $itemModel = new MenuItem();
        $items = $itemModel->all();
        
        if (empty($items)) {
            $items = [
                ['id' => 1, 'name' => 'Shio Ramen', 'price' => 16.50, 'image_url' => '/img/item_shio_ramen.png', 'badges' => '["signature"]', 'is_available' => 1],
                ['id' => 2, 'name' => 'Tonkotsu Ramen', 'price' => 18.00, 'image_url' => '/img/item_tonkotsu.png', 'badges' => '["signature"]', 'is_available' => 1],
                ['id' => 3, 'name' => 'Tantanmen', 'price' => 17.50, 'image_url' => '/img/item_tantanmen.png', 'badges' => '["spicy"]', 'is_available' => 1],
                ['id' => 4, 'name' => 'Gyoza Grillés (x6)', 'price' => 8.50, 'image_url' => '/img/item_gyoza.png', 'badges' => '[]', 'is_available' => 1],
                ['id' => 5, 'name' => 'Karaage', 'price' => 9.50, 'image_url' => '/img/item_karaage.png', 'badges' => '[]', 'is_available' => 1],
                ['id' => 6, 'name' => 'Edamame', 'price' => 5.50, 'image_url' => '/img/item_edamame.png', 'badges' => '["veggie"]', 'is_available' => 1],
                ['id' => 7, 'name' => 'Mochi Glacé (x2)', 'price' => 6.50, 'image_url' => '/img/item_mochi.png', 'badges' => '[]', 'is_available' => 1],
                ['id' => 8, 'name' => 'Saké Namachozo 30cl', 'price' => 15.00, 'image_url' => '/img/item_sake.png', 'badges' => '[]', 'is_available' => 1],
                ['id' => 9, 'name' => 'Dorayaki', 'price' => 7.00, 'image_url' => '/img/item_dorayaki.png', 'badges' => '[]', 'is_available' => 1]
            ];
        }
        
        $data = [
            'title' => 'Gestion de la Carte',
            'items' => $items
        ];
        $this->render('admin/menu/index', $data, 'admin');
    }

    public function create()
    {
        $categoryModel = new MenuCategory();
        $data = [
            'title' => 'Ajouter un plat',
            'categories' => $categoryModel->all()
        ];
        $this->render('admin/menu/form', $data, 'admin');
    }

    public function store()
    {
        $itemModel = new MenuItem();
        
        $imageUrl = null;
        if (!empty($_FILES['image']['name'])) {
            $imageUrl = MediaService::upload($_FILES['image'], 'menu');
        }

        $badges = isset($_POST['badges']) ? json_encode($_POST['badges']) : '[]';

        $itemModel->create([
            'category_id' => $_POST['category_id'],
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'image_url' => $imageUrl,
            'badges' => $badges,
            'is_available' => isset($_POST['is_available']) ? 1 : 0,
            'display_order' => $_POST['display_order'] ?? 0
        ]);

        $this->redirect('/admin/menu');
    }

    public function edit($id)
    {
        $itemModel = new MenuItem();
        $categoryModel = new MenuCategory();
        
        $item = $itemModel->find($id);
        if (!$item) $this->redirect('/admin/menu');

        $data = [
            'title' => 'Modifier : ' . $item['name'],
            'item' => $item,
            'categories' => $categoryModel->all()
        ];
        $this->render('admin/menu/form', $data, 'admin');
    }

    public function update($id)
    {
        $itemModel = new MenuItem();
        $item = $itemModel->find($id);
        
        $data = [
            'category_id' => $_POST['category_id'],
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'is_available' => isset($_POST['is_available']) ? 1 : 0,
            'display_order' => $_POST['display_order'] ?? 0
        ];

        if (!empty($_FILES['image']['name'])) {
            if ($item['image_url']) MediaService::delete($item['image_url']);
            $data['image_url'] = MediaService::upload($_FILES['image'], 'menu');
        }

        if (isset($_POST['badges'])) {
            $data['badges'] = json_encode($_POST['badges']);
        }

        $itemModel->update($id, $data);
        $this->redirect('/admin/menu');
    }

    public function delete($id)
    {
        $itemModel = new MenuItem();
        $item = $itemModel->find($id);
        if ($item && $item['image_url']) MediaService::delete($item['image_url']);
        
        $itemModel->delete($id);
        $this->redirect('/admin/menu');
    }
}
