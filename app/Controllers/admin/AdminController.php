<?php
namespace App\Controllers\admin;

use App\Core\Controller;

class AdminController extends Controller
{
    public function dashboard()
    {

        // Accès réservé aux admins connectés
        if (empty($_SESSION["admin_logged"])) {
            header("Location: /admin/login");
            exit;
        }

        // Envoi à la vue
        $this->render("admin/dashboard", [], "layout_admin");
    }
}