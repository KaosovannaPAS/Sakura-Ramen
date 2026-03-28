<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\TeamMember;
use App\Middlewares\AuthMiddleware;
use App\Services\MediaService;

class TeamController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::check();
    }

    public function index()
    {
        $teamModel = new TeamMember();
        $members = $teamModel->all();
        
        if (empty($members)) {
            $members = [
                ['id' => 1, 'name' => 'Kenji Tanaka', 'role' => 'Chef Exécutif', 'image_url' => '/img/team_chef.png', 'display_order' => 1, 'is_active' => 1],
                ['id' => 2, 'name' => 'Aiko Watanabe', 'role' => 'Fondatrice', 'image_url' => '/img/team_founder.png', 'display_order' => 2, 'is_active' => 1],
                ['id' => 3, 'name' => 'Nicolas', 'role' => 'Sous-Chef', 'image_url' => '/img/team_nicolas.jpg', 'display_order' => 3, 'is_active' => 1],
                ['id' => 4, 'name' => 'Léa', 'role' => 'Chef de Salle', 'image_url' => '/img/team_lea.jpg', 'display_order' => 4, 'is_active' => 1],
                ['id' => 5, 'name' => 'Thomas', 'role' => 'Serveur', 'image_url' => '/img/team_thomas.jpg', 'display_order' => 6, 'is_active' => 1],
                ['id' => 6, 'name' => 'Emma', 'role' => 'Serveuse', 'image_url' => '/img/team_emma.jpg', 'display_order' => 7, 'is_active' => 1],
            ];
        }
        
        $data = [
            'title' => 'Gestion de l\'Équipe',
            'members' => $members
        ];
        $this->render('admin/team/index', $data, 'admin');
    }

    public function create()
    {
        $data = [
            'title' => 'Ajouter un membre'
        ];
        $this->render('admin/team/form', $data, 'admin');
    }

    public function store()
    {
        $teamModel = new TeamMember();
        
        $imageUrl = null;
        if (!empty($_FILES['image']['name'])) {
            $imageUrl = MediaService::upload($_FILES['image'], 'team');
        }

        $teamModel->create([
            'name' => $_POST['name'],
            'role' => $_POST['role'],
            'bio' => $_POST['bio'],
            'image_url' => $imageUrl,
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'display_order' => $_POST['display_order'] ?? 0
        ]);

        $this->redirect('/admin/team');
    }

    public function edit($id)
    {
        $teamModel = new TeamMember();
        $member = $teamModel->find($id);
        if (!$member) $this->redirect('/admin/team');

        $data = [
            'title' => 'Modifier : ' . $member['name'],
            'member' => $member
        ];
        $this->render('admin/team/form', $data, 'admin');
    }

    public function update($id)
    {
        $teamModel = new TeamMember();
        $member = $teamModel->find($id);
        
        $data = [
            'name' => $_POST['name'],
            'role' => $_POST['role'],
            'bio' => $_POST['bio'],
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'display_order' => $_POST['display_order'] ?? 0
        ];

        if (!empty($_FILES['image']['name'])) {
            if ($member['image_url']) MediaService::delete($member['image_url']);
            $data['image_url'] = MediaService::upload($_FILES['image'], 'team');
        }

        $teamModel->update($id, $data);
        $this->redirect('/admin/team');
    }

    public function delete($id)
    {
        $teamModel = new TeamMember();
        $member = $teamModel->find($id);
        if ($member && $member['image_url']) MediaService::delete($member['image_url']);
        
        $teamModel->delete($id);
        $this->redirect('/admin/team');
    }
}
