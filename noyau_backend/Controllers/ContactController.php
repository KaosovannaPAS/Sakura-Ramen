<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use PDO;

class ContactController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Contact & Infos Pratiques — Sakura Ramen',
            'meta_description' => 'Contactez l\'équipe de Sakura Ramen à Vannes. Retrouvez nos horaires, notre adresse et envoyez-nous un message.'
        ];
        $this->render('contact', $data);
    }

    public function send()
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $subject = $_POST['subject'] ?? '';
        $message = $_POST['message'] ?? '';

        if (empty($name) || empty($email) || empty($message)) {
            $_SESSION['error'] = "Tous les champs obligatoires doivent être remplis.";
            $this->redirect('/contact');
        }

        $db = Database::getMySQL();
        $stmt = $db->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $subject, $message]);

        $_SESSION['success'] = "Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.";
        $this->redirect('/contact');
    }
}
