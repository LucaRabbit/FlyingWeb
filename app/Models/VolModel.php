<?php
// Namespace
namespace App\Models;

// Imports
use PDO;
use App\Core\Model;

// Déclaration de la classe VolModel
// Hérite de classe Model (connexion PDO, méthodes CRUD)
class VolModel extends Model
{
    // Nom de la table
    protected string $table = "vol";

    // Définition de la clé primaire
    protected string $primaryKey = "IdVol";

    // Rechercher des vols
    public function searchVols(array $aeroportsDepart, array $aeroportsArrivee, $date)
    {
        // Extraire les IDs
        $idsDepart = array_column($aeroportsDepart, "IdAeroport");
        $idsArrivee = array_column($aeroportsArrivee, "IdAeroport");

        // Générer les placeholders SQL
        $inDepart = implode(",", array_fill(0, count($idsDepart), "?"));
        $inArrivee = implode(",", array_fill(0, count($idsArrivee), "?"));

        $sql = "SELECT *
                FROM VOL
                WHERE IdAeroportDepart IN ($inDepart)
                AND IdAeroportArrivee IN ($inArrivee)
                AND DATE(DateHeureDepartUTC) = ?";

        $params = array_merge($idsDepart, $idsArrivee, [$date]);

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Rechercher des vols selon un eplage de dates donnée
    public function searchVolsPlage(array $aeroportsDepart, array $aeroportsArrivee, $dateMin, $dateMax)
    {
        $idsDepart = array_column($aeroportsDepart, "IdAeroport");
        $idsArrivee = array_column($aeroportsArrivee, "IdAeroport");

        $inDepart = implode(",", array_fill(0, count($idsDepart), "?"));
        $inArrivee = implode(",", array_fill(0, count($idsArrivee), "?"));

        $sql = "SELECT 
                    VOL.*,
                    AD.NomAeroport AS AeroportDepartNom,
                    AD.Ville AS VilleDepart,
                    AA.NomAeroport AS AeroportArriveeNom,
                    AA.Ville AS VilleArrivee
                FROM VOL
                JOIN AEROPORT AD ON VOL.IdAeroportDepart = AD.IdAeroport
                JOIN AEROPORT AA ON VOL.IdAeroportArrivee = AA.IdAeroport
                WHERE VOL.IdAeroportDepart IN ($inDepart)
                AND VOL.IdAeroportArrivee IN ($inArrivee)
                AND DATE(VOL.DateHeureDepartUTC) BETWEEN ? AND ?
                AND VOL.DateHeureDepartUTC > NOW()
                AND VOL.StatutVol NOT IN ('Arrive', 'EnCours', 'Annule')
                ORDER BY VOL.DateHeureDepartUTC ASC";

        $params = array_merge($idsDepart, $idsArrivee, [$dateMin, $dateMax]);

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les vols associés à un avion donné
    public function findByAvion($idAvion)
    {
        $sql = "SELECT * FROM VOL WHERE IdAvion = ? ORDER BY DateHeureDepartUTC ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idAvion]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les vols associés à un aéroport
    public function findByAeroport($idAeroport)
    {
        $sql = "SELECT * FROM VOL 
                WHERE IdAeroportDepart = ? 
                OR IdAeroportArrivee = ?
                ORDER BY DateHeureDepartUTC ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idAeroport, $idAeroport]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Vérifier si un avion est déjà utilisé sur une période donnée
    public function avionOccupe($idAvion, $depart, $arrivee, $idVolIgnore = null)
    {
        $sql = "SELECT COUNT(*) 
                FROM VOL
                WHERE IdAvion = ?
                AND StatutVol != 'Annule'
                AND (
                        DateHeureDepartUTC < ?
                    AND DateHeureArriveeUTC > ?
                )";

        // Si modification vol existant, exclusion de la vérification
        if ($idVolIgnore !== null) {
            $sql .= " AND IdVol != ?";
        }

        $stmt = $this->db->prepare($sql);

        // Construction dynamique des paramètres
        $params = [$idAvion, $arrivee, $depart];

        if ($idVolIgnore !== null) {
            $params[] = $idVolIgnore;
        }

        $stmt->execute($params);

        return $stmt->fetchColumn() > 0;
    }

    // Vérifier si un avion est utilisé dans un vol futur
    public function avionUtiliseDansUnVolFutur($idAvion)
    {
        $now = date("Y-m-d H:i:s");

        $sql = "SELECT * FROM VOL 
                WHERE IdAvion = ?
                AND DateHeureDepartUTC >= ?
                AND StatutVol NOT IN ('Annule', 'Arrive')";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idAvion, $now]);

        return !empty($stmt->fetchAll());
    }

    // Vérifier si un aéroport est utilisé dans un vol futur
    public function aeroportUtiliseDansUnVolFutur($idAeroport)
    {
        $today = date("Y-m-d");

        $sql = "SELECT COUNT(*)
                FROM VOL
                WHERE (IdAeroportDepart = ? OR IdAeroportArrivee = ?)
                AND DATE(DateHeureDepartUTC) >= ?
                AND StatutVol != 'Annule' AND StatutVol !='Arrive'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idAeroport, $idAeroport, $today]);

        return $stmt->fetchColumn() > 0;
    }

    // Récupérer le dernier vol ou vol planifié d'un avion
    public function getDernierVolAvant($idAvion, $dateDepart, $idVolExclu = null)
    {
        $params = [$idAvion, $dateDepart];

        $sql = "SELECT *
                FROM VOL
                WHERE IdAvion = ?
                AND DateHeureDepartUTC < ?
                ";

        if ($idVolExclu !== null) {
            $sql .= " AND IdVol != ? ";
            $params[] = $idVolExclu;
        }

        $sql .= " ORDER BY DateHeureArriveeUTC DESC LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récuperer les passagers d'un vol
    public function getPassagersByVol($idVol)
    {
        // Récupérer le statut du vol
        $sqlVol = "SELECT StatutVol FROM VOL WHERE IdVol = ?";
        $stmtVol = $this->db->prepare($sqlVol);
        $stmtVol->execute([$idVol]);
        $statutVol = $stmtVol->fetchColumn();

        // Construire la requête selon le statut du vol
        $sql = "SELECT 
                    p.IdPassager,
                    p.Nom,
                    p.Prenom,
                    rp.NumeroSiege
                FROM RESERVATION_PASSAGER rp
                JOIN PASSAGER p ON p.IdPassager = rp.IdPassager
                JOIN RESERVATION r ON r.IdReservation = rp.IdReservation
                WHERE rp.IdVol = ?";

        if ($statutVol === 'Annule') {
            // Vol annulé : afficher les réservations annulées à cause du vol
            $sql .= " AND r.StatutReservation = 'AnnuleeVol'";
        } else {
            // Vol actif : afficher les réservations normales
            $sql .= " AND r.StatutReservation NOT IN ('Annulee', 'AnnuleeVol')";
        }

        $sql .= " ORDER BY rp.NumeroSiege";

        //Exécuter la requete
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idVol]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer tous les vols
    public function findAllWithAeroports()
    {
        $sql = "SELECT 
                    VOL.*,
                    AD.NomAeroport AS AeroportDepartNom,
                    AD.Ville AS VilleDepart,
                    AA.NomAeroport AS AeroportArriveeNom,
                    AA.Ville AS VilleArrivee
                FROM VOL
                JOIN AEROPORT AD ON VOL.IdAeroportDepart = AD.IdAeroport
                JOIN AEROPORT AA ON VOL.IdAeroportArrivee = AA.IdAeroport
                WHERE VOL.DateHeureDepartUTC > NOW()
                AND VOL.StatutVol NOT IN ('Arrive', 'EnCours', 'Annule')
                ORDER BY VOL.DateHeureDepartUTC ASC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récuperer un vol avec les informations des aéroports
    public function findByIdWithAeroports($id)
    {
        $sql = "SELECT 
                    VOL.*,
                    AD.NomAeroport AS AeroportDepartNom,
                    AD.Ville AS VilleDepart,
                    AA.NomAeroport AS AeroportArriveeNom,
                    AA.Ville AS VilleArrivee
                FROM VOL
                JOIN AEROPORT AD ON VOL.IdAeroportDepart = AD.IdAeroport
                JOIN AEROPORT AA ON VOL.IdAeroportArrivee = AA.IdAeroport
                WHERE VOL.IdVol = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Vérifier si un avion est modifiable
    public function avionModifiable($idAvion): bool
    {
        if ($this->avionUtiliseDansUnVolFutur($idAvion)) {
            return false;
        }

        return true;
    }


}