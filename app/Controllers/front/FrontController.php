<?php
namespace App\Controllers\front;

use App\Core\Controller;

class FrontController extends Controller
{
    public function dashboard()
    {
        // Envoi à la vue
        $this->render("front/dashboard", [], "layout_front");
    }
}