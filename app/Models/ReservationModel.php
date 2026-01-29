<?php
namespace App\Models;

use App\Core\Model;
use PDO;

use App\Models\VolModel;

class ReservationModel extends Model
{
    protected string $table = "reservation";
    protected string $primaryKey = "IdReservation";

    private VolModel $volModel;

    public function __construct()
        {
            parent::__construct();
            $this->volModel = new VolModel();
        }

    // Récupérer les passagers d'une réservation
    public function getPassagers($idReservation)
    {
        $sql = "SELECT 
                    p.IdPassager,
                    p.Nom,
                    p.Prenom,
                    rp.NumeroSiege,
                    rp.IdVol
                FROM RESERVATION_PASSAGER rp
                JOIN PASSAGER p ON p.IdPassager = rp.IdPassager
                WHERE rp.IdReservation = ?
                ORDER BY p.IdPassager, rp.IdVol";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idReservation]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Compter les passagers uniques
    public function countUniquePassagers($idReservation)
    {
        $sql = "SELECT COUNT(DISTINCT IdPassager)
                FROM RESERVATION_PASSAGER
                WHERE IdReservation = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idReservation]);
        return $stmt->fetchColumn();
    }

    // Récupérer le vol Aller lié à la réservation
    public function getVolAller($idReservation)
    {
        $sql = "SELECT v.*
                FROM VOL v
                JOIN RESERVATION r ON r.IdVolAller = v.IdVol
                WHERE r.IdReservation = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idReservation]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer le vol Retour lié à la réservation
    public function getVolRetour($idReservation)
    {
        $sql = "SELECT v.*
                FROM VOL v
                JOIN RESERVATION r ON r.IdVolRetour = v.IdVol
                WHERE r.IdReservation = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idReservation]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Ajouter un passager
    public function addPassager($nom, $prenom)
    {
        $sql = "INSERT INTO PASSAGER (Nom, Prenom) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nom, $prenom]);

        return $this->db->lastInsertId();
    }

    // Associer un passager à une réservation
    public function attachPassagerToReservation($idReservation, $idPassager, $numeroSiege, $idVol)
    {
        // Attribuer automatiquement un siège
        $numeroSiege = $this->getFirstAvailableSeat($idVol);

        if ($numeroSiege === null) {
            throw new \Exception("Aucun siège disponible sur ce vol.");
        }

        $sql = "INSERT INTO RESERVATION_PASSAGER (NumeroSiege, IdReservation, IdPassager, IdVol)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$numeroSiege, $idReservation, $idPassager, $idVol]);
    }


    // Détacher un passager d'une réservation
    public function detachPassagerFromReservation($idReservation, $idPassager)
    {
        $sql = "DELETE FROM RESERVATION_PASSAGER 
                WHERE IdReservation = ? AND IdPassager = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idReservation, $idPassager]);
    }

    // Annuler les réservations d'un vol
    public function annulerReservationsDuVol($idVol)
    {
        $sql = "UPDATE RESERVATION
                SET StatutReservation = 'AnnuleeVol'
                WHERE (IdVolRetour = ? OR IdVolAller = ?)
                AND StatutReservation != 'Annulee'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idVol, $idVol]);
    }

    // Annuler une réservation (manuel)
    public function cancelReservation($id)
    {
        // Libérer les sièges
        $sql = "DELETE FROM RESERVATION_PASSAGER WHERE IdReservation = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        // Annuler la réservation
        $sql = "UPDATE RESERVATION 
                SET StatutReservation = 'Annulee' 
                WHERE IdReservation = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
    }

    // Créer une réservation
    public function create(array $data)
    {
        $sql = "INSERT INTO RESERVATION 
                (DateReservation, EmailReservant, NbPassagersReserve, TokenLien, IdVolAller, IdVolRetour)
                VALUES (:DateReservation, :EmailReservant, :NbPassagersReserve, :TokenLien, :IdVolAller, :IdVolRetour)";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(":DateReservation", $data["DateReservation"]);
        $stmt->bindValue(":EmailReservant", $data["EmailReservant"]);
        $stmt->bindValue(":NbPassagersReserve", $data["NbPassagersReserve"]);
        $stmt->bindValue(":TokenLien", $data["TokenLien"]);
        $stmt->bindValue(":IdVolAller", $data["IdVolAller"]);
        $stmt->bindValue(":IdVolRetour", $data["IdVolRetour"], $data["IdVolRetour"] === null ? PDO::PARAM_NULL : PDO::PARAM_INT);

        $stmt->execute();

        return $this->db->lastInsertId();
    }

    // Récupérer un siege disponible
    public function getFirstAvailableSeat($idVol)
    {
        // Récupérer le nombre total de sièges de l'avion
        $sql = "SELECT NbPlacesPassager 
                FROM AVION 
                JOIN VOL ON VOL.IdAvion = AVION.IdAvion
                WHERE VOL.IdVol = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idVol]);
        $nbPlaces = $stmt->fetchColumn();

        // Récupérer les sièges déjà pris
        $sql = "SELECT NumeroSiege 
                FROM RESERVATION_PASSAGER 
                WHERE IdVol = ?
                ORDER BY NumeroSiege ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idVol]);
        $takenSeats = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Trouver le premier siège libre
        for ($i = 1; $i <= $nbPlaces; $i++) {
            if (!in_array($i, $takenSeats)) {
                return $i;
            }
        }
        // Si vol complet
        return null;
    }

    // Vérifier si un vol est complet
    public function isVolComplet($idVol)
    {
        // Nombre total de sièges
        $sql = "SELECT NbPlacesPassager 
                FROM AVION 
                JOIN VOL ON VOL.IdAvion = AVION.IdAvion
                WHERE VOL.IdVol = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idVol]);
        $nbPlaces = $stmt->fetchColumn();

        // Nombre de sièges déjà réservés
        $sql = "SELECT COUNT(*) 
                FROM RESERVATION_PASSAGER 
                WHERE IdVol = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idVol]);
        $nbReserves = $stmt->fetchColumn();

        return $nbReserves >= $nbPlaces;
    }

    // Récupérer les places restantes d'un vol
    public function getPlacesRestantes($idVol)
    {
        // Nombre total de sièges
        $sql = "SELECT NbPlacesPassager 
                FROM AVION 
                JOIN VOL ON VOL.IdAvion = AVION.IdAvion
                WHERE VOL.IdVol = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idVol]);
        $nbPlaces = $stmt->fetchColumn();

        // Nombre de sièges déjà réservés
        $sql = "SELECT COUNT(*) 
                FROM RESERVATION_PASSAGER 
                WHERE IdVol = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idVol]);
        $nbReserves = $stmt->fetchColumn();

        return $nbPlaces - $nbReserves;
    }

    // Récuperer une réservation via token
    public function findByToken($token)
    {
        $sql = "SELECT * FROM RESERVATION WHERE TokenLien = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$token]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Recalculer le nombre de passagers d'une réservation
    public function recalcPassagerCount($idReservation)
    {
        $sql = "UPDATE RESERVATION
                SET NbPassagersReserve = (
                    SELECT COUNT(DISTINCT IdPassager) 
                    FROM RESERVATION_PASSAGER 
                    WHERE IdReservation = ?
                )
                WHERE IdReservation = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idReservation, $idReservation]);
    }

    // Vérifier si une réservation est modifiable
    public function reservationModifiable(array $reservation): bool
    {
        if (in_array($reservation["StatutReservation"], ["Annulee", "AnnuleeVol", "Cloturee"])) {
            return false;
        }

        $vol = $this->volModel->findById($reservation["IdVolAller"]);

        if (in_array($vol["StatutVol"], ["EnCours", "Arrive", "Annule"])) {
            return false;
        }

        return true;
    }

    // Confirmer toutes les réservations d'un vol
    public function confirmerReservationsDuVol($idVol)
    {
        $idVol = (int)$idVol;

        $sql = "UPDATE reservation
                SET StatutReservation = 'Confirmee'
                WHERE (IdVolAller = $idVol OR IdVolRetour = $idVol)
                AND StatutReservation = 'EnAttente'";

        $this->db->query($sql);
    }

    // Clôturer toute sles réservations d'un vol
    public function cloturerReservationsDuVol($idVol)
    {
        $idVol = (int)$idVol;

        $sql = "
            UPDATE reservation r
            JOIN vol vA ON r.IdVolAller = vA.IdVol
            LEFT JOIN vol vR ON r.IdVolRetour = vR.IdVol
            SET r.StatutReservation = 'Cloturee'
            WHERE (r.IdVolAller = $idVol OR r.IdVolRetour = $idVol)
            AND r.StatutReservation = 'Confirmee'
            AND vA.StatutVol = 'Arrive'
            AND (r.IdVolRetour IS NULL OR vR.StatutVol = 'Arrive')
        ";

        $this->db->query($sql);
    }

}