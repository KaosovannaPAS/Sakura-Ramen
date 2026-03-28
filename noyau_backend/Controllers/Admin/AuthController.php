<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use PDO;

class AuthController extends Controller
{
    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            $this->redirect('/admin');
        }

        $this->render('admin/login', [], 'blank');
    }

    public function authenticate()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        try {
            $db = Database::getMySQL();
            $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role_id'] = $user['role_id'];
                $this->redirect('/admin');
                return;
            }
        } catch (\PDOException $e) {
            // Fallback for Vercel demonstration if DB is not connected
            if (($username === 'admin' || $username === 'admin@sakura-ramen.fr') && $password === 'password') {
                $_SESSION['user_id'] = 1;
                $_SESSION['username'] = 'admin';
                $_SESSION['role_id'] = 1;
                $this->redirect('/admin');
                return;
            }
            $_SESSION['error'] = "Erreur de connexion à la base de données. Mode démo disponible (admin/password).";
            $this->redirect('/admin/login');
            return;
        }

        $_SESSION['error'] = "Identifiants invalides.";
        $this->redirect('/admin/login');
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        $this->redirect('/admin/login');
    }
}
