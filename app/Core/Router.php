<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    // Déclarer une route GET
    public function get(string $path, callable|array $action): void
    {
        $this->addRoute('GET', $path, $action);
    }

    // Déclarer une route POST
    public function post(string $path, callable|array $action): void
    {
        $this->addRoute('POST', $path, $action);
    }

    // Ajouter une route (GET ou POST)
    private function addRoute(string $method, string $path, callable|array $action): void
    {
        // Convertir {param} en regex nommée
        $pattern = preg_replace(
            '#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#',
            '(?P<$1>[^/]+)',
            $path
        );

        // Ajouter les délimiteurs regex
        $pattern = "#^" . $pattern . "$#";

        // Stocker la route
        $this->routes[$method][] = [
            'pattern' => $pattern,
            'action'  => $action
        ];
    }

    // Exécuter la route demandée
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');

        // Vérifier si des routes existent pour cette méthode
        if (!isset($this->routes[$method])) {
            $this->send404();
            return;
        }

        // Parcourt toutes les routes de cette méthode
        foreach ($this->routes[$method] as $route) {

            // Vérifier si l'URL correspond au pattern
            if (preg_match($route['pattern'], $uri, $matches)) {

                // Récupèrer uniquement les paramètres nommés
                $params = array_filter(
                    $matches,
                    fn($key) => !is_int($key),
                    ARRAY_FILTER_USE_KEY
                );

                $action = $route['action'];

                // Contrôleur + méthode
                if (is_array($action)) {
                    [$controller, $methodName] = $action;

                    if (!class_exists($controller)) {
                        throw new \Exception("Contrôleur introuvable : $controller");
                    }

                    $controllerInstance = new $controller();

                    if (!method_exists($controllerInstance, $methodName)) {
                        throw new \Exception("Méthode introuvable : $methodName dans $controller");
                    }

                    // Exécute l'action
                    $controllerInstance->$methodName(...$params);
                    return;
                }

                // Fonction anonyme
                $action(...$params);
                return;
            }
        }

        // Aucune route trouvée
        $this->send404();
    }

    // Envoyer une page 404
    private function send404(): void
    {
        http_response_code(404);
        echo "Erreur 404 — Page non existante";
    }
}