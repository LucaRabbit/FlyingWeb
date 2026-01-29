<?php
// Namespace
namespace App\Models;

// Imports
use App\Core\Model;
use PDO;

// Déclaration de la classe AvionModel
// Hérite de classe Model (connexion PDO, méthodes CRUD)
class AvionModel extends Model
{
    // Nom de la table
    protected string $table = "avion";

    // Définition de la clé primaire
    protected string $primaryKey = 'IdAvion';
    
    // Récupérer les avions stationnés dans un aéroport
    public function findByAeroport($idAeroport)
    {
        $sql = "SELECT * FROM AVION WHERE IdAeroportActuel = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idAeroport]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Vérifier si des avions sont stationnés dans un aéroport
    public function avionsStationnesDansAeroport($idAeroport)
    {
        $sql = "SELECT COUNT(*) FROM AVION WHERE IdAeroportActuel = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idAeroport]);

        return $stmt->fetchColumn() > 0;
    }

}