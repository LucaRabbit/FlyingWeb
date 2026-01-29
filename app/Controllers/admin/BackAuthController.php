<?php
namespace App\Controllers\admin;

use App\Core\Controller;

use App\Models\AdminModel;

class BackAuthController extends Controller
{
    private AdminModel $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    // Formulaire d'authentification
    public function loginForm()
    {
        $this->render("admin/login");
    }

    // Authentification
    public function login()
    {
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Récupérer l'email
        $admin = $this->adminModel->findByEmail($email);

        // Authentification validée
        if ($admin && password_verify($password, $admin["MotDePasse"])) {
            $_SESSION["admin_logged"] = true;

            // Redirection
            header("Location: /admin/dashboard");
            exit;
        }

        // Authentification refusée
        $this->render("admin/login", ["error" => "Identifiants incorrects"]);
    }

    // Deconnexion
    public function logout()
    {
        unset($_SESSION["admin_logged"]);
        
        // Redirection
        header("Location: /admin/login");
        exit;
    }
}