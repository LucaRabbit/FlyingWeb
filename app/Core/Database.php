<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    // Singleton : une seule instance PDO pour toute l'application
    private static ?PDO $pdo = null;

    // Retourne une connexion PDO unique
    public static function getConnection(): PDO
    {
        // Créer la connexion si inexistante
        if (self::$pdo === null) {

            // Chargement de la configuration
            $config = require __DIR__ . '/../../config/database.php';

            // Construction du DSN
            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=%s",
                $config['host'],
                $config['dbname'],
                $config['charset']
            );

            try {
                // Création de la connexion PDO
                self::$pdo = new PDO(
                    $dsn,
                    $config['user'],
                    $config['password'],
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false
                    ]
                );

            } catch (PDOException $e) {
                // Message d'erreur clair et sécurisé
                die("Erreur de connexion à la base de données.");
            }
        }

        return self::$pdo;
    }
}