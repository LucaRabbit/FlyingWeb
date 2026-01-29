<?php

namespace App\Core;

use App\Core\View;

class Controller
{
    // Empêche un double rendu dans la même requête
    private bool $rendered = false;

    // Rendu d'une vue avec layout
    protected function render(string $view, array $params = [], string $layout = "layout_front")
    {
        if ($this->rendered) {
            throw new \Exception("La vue a déjà été rendue dans ce contrôleur.");
        }

        $this->rendered = true;

        $v = new View();
        
        $v->render($view, $params, $layout);
    }

    // Redirection HTTP
    protected function redirect(string $url)
    {
        header("Location: " . $url);
        exit;
    }


    // Log les actions métiers
    protected function logCustom($action, $message, $context = null)
    {
        // Récupérer la connexion PDO
        $db = \App\Core\Database::getConnection();

        $sql = "INSERT INTO logs (TableName, Action, RecordId, OldData, NewData)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            "SYSTEM",
            $action,
            0,
            null,
            json_encode([
                "message" => $message,
                "context" => $context
            ])
        ]);
    }
}