<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        // Envoi à la vue
        $this->render("home/index", [], "layout_public");
    }
}