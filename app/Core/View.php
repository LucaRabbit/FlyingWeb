<?php

namespace App\Core;

class View
{
    /**
     * Rend une vue avec un layout
     */
    public function render(string $view, array $params = [], string $layout = "layout_front")
    {
        // Transforme les clés du tableau en variables
        if (!empty($params)) {
            extract($params, EXTR_SKIP); // EXTR_SKIP évite d'écraser des variables internes
        }

        // Path de la vue
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new \Exception("Vue introuvable : $viewPath");
        }

        // Capture du contenu de la vue
        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        // Path du layout
        $layoutPath = __DIR__ . '/../Views/' . $layout . '.php';

        if (!file_exists($layoutPath)) {
            throw new \Exception("Layout introuvable : $layoutPath");
        }

        // Inclusion du layout
        require $layoutPath;
    }
}