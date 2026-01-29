<?php
// Namespace
namespace App\Models;

// Imports
use App\Core\Model;
use PDO;

// Déclaration de la classe AeroportModel
// Hérite de classe Model (connexion PDO, méthodes CRUD)
class AeroportModel extends Model
{
    // Nom de la table
    protected string $table = "aeroport";

    // Définition de la clé primaire
    protected string $primaryKey = "IdAeroport";

    // Convertir une ville en liste d'aéroports
    public function findByVille($ville)
    {
        $sql = "SELECT * FROM AEROPORT WHERE Ville LIKE ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["%$ville%"]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les villes des aéroports
    public function getAllVilles()
    {
        $sql = "SELECT DISTINCT Ville FROM AEROPORT ORDER BY Ville ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

}