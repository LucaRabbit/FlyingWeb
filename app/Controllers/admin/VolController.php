<?php
namespace App\Controllers\admin;

use App\Core\Controller;
use App\Models\VolModel;
use App\Models\AvionModel;
use App\Models\AeroportModel;
use App\Models\ReservationModel;

use App\Helpers\Flash;
use Exception;

class VolController extends Controller
{
    private VolModel $volModel;
    private AvionModel $avionModel;
    private AeroportModel $aeroportModel;
    private ReservationModel $reservationModel;

    public function __construct()
    {

        // Accès réservé aux admins connectés
        if (empty($_SESSION["admin_logged"])) {
            header("Location: /admin/login");
            exit;
        }

        $this->volModel = new VolModel();
        $this->avionModel = new AvionModel();
        $this->aeroportModel = new AeroportModel();
        $this->reservationModel = new ReservationModel();
    }

    // Liste des vols
    public function index()
    {
        // Lister tous les vols
        $vols = $this->volModel->findAll();

        // Envoi à la vue
        $this->render("admin/vol/index", ["vols" => $vols], "layout_admin");
    }

    // Formulaire de création
    public function create()
    {
        // Lister tous les avions
        $avions = $this->avionModel->findAll();

        // Lister tous les aéroports
        $aeroports = $this->aeroportModel->findAll();

        // Envoi à la vue
        $this->render("admin/vol/create", [
            "avions" => $avions,
            "aeroports" => $aeroports
        ], "layout_admin");
    }

    // Enregistrement du vol
    public function store()
    {
        try {
            $depart = $_POST["DateHeureDepartUTC"];
            $arrivee = $_POST["DateHeureArriveeUTC"];
            $idAvion = $_POST["IdAvion"];
            $idAeroportDepart = $_POST["IdAeroportDepart"];
            $idAeroportArrivee = $_POST["IdAeroportArrivee"];
            $now = date("Y-m-d H:i:s");

            // Vérifier que l'avion existe
            $avion = $this->avionModel->findById($idAvion);
            if (!$avion) {
                throw new \Exception("Avion introuvable.");
            }

            // Empêcher de planifier un vol avec un avion indisponible
            if ($avion["StatutAvion"] === "Maintenance" || $avion["StatutAvion"] === "HorsService") {
                throw new \Exception("Impossible de planifier ce vol : l’avion est indisponible (maintenance ou hors service).");
            }

            // Empêcher une date de départ dans le passé
            if (strtotime($depart) < strtotime($now)) {
                throw new \Exception("Erreur : la date de départ ne peut pas être dans le passé.");
            }

            // Empêcher une date d'arrivée dans le passé
            if (strtotime($arrivee) < strtotime($now)) {
                throw new \Exception("Erreur : la date d'arrivée ne peut pas être dans le passé.");
            }

            // Empêcher une arrivée égale ou antérieure au départ
            if (strtotime($arrivee) <= strtotime($depart)) {
                throw new \Exception("Erreur : la date d'arrivée doit être strictement supérieure à la date de départ.");
            }

            // Empêcher la sélection d'un avion occupé
            if ($this->volModel->avionOccupe($idAvion, $depart, $arrivee)) {
                throw new \Exception("Impossible de planifier ce vol : l’avion est déjà utilisé sur cette période.");
            }

            // Vérifier que l’avion se trouve/trouvera dans l’aéroport de départ
            $dernierVol = $this->volModel->getDernierVolAvant($idAvion, $depart);

            if ($dernierVol) {
                $aeroportFinal = $dernierVol["IdAeroportArrivee"];

                // Empêcher la planification du vol si l'avion choisi n'est pas disponible
                if ($aeroportFinal != $idAeroportDepart) {
                    throw new \Exception("Impossible de planifier ce vol : l’avion sera à un autre aéroport à cette date.");
                }
            } else {
                // SI aucun vol avant, récupérer l'aéroport actuel de l'avion
                if ($avion["IdAeroportActuel"] != $idAeroportDepart) {
                    throw new \Exception("Impossible de planifier ce vol : l’avion ne se trouve pas dans cet aéroport.");
                }
            }

            // Vérifier que l'aéroport de départ peut accueillir l'avion
            $aeroportDepart = $this->aeroportModel->findById($idAeroportDepart);
            if ($avion["LongueurAvion"] > $aeroportDepart["LongueurAvionMax"]) {
                throw new \Exception("Impossible de planifier ce vol : l'aéroport de départ ne peut pas accueillir cet avion.");
            }

            // Vérifier que l'aéroport d'arrivée peut accueillir l'avion
            $aeroportArrivee = $this->aeroportModel->findById($idAeroportArrivee);
            if ($avion["LongueurAvion"] > $aeroportArrivee["LongueurAvionMax"]) {
                throw new \Exception("Impossible de planifier ce vol : l'aéroport d'arrivée ne peut pas accueillir cet avion.");
            }

            // Enregistrement du vol
            $data = [
                "NumeroVol"           => $_POST["NumeroVol"],
                "DateHeureDepartUTC"  => $depart,
                "DateHeureArriveeUTC" => $arrivee,
                "StatutVol"           => $_POST["StatutVol"],
                "IdAvion"             => $idAvion,
                "IdAeroportDepart"    => $idAeroportDepart,
                "IdAeroportArrivee"   => $idAeroportArrivee
            ];

            // Création du vol
            $this->volModel->create($data);

            // Mettre à jour la position de l’avion (aéroport de départ)
            $this->avionModel->update($idAvion, [
                "IdAeroportActuel" => $idAeroportDepart
            ]);

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Redirection
        $this->redirect("/admin/vol");
    }

    // Affichage d'un vol
    public function show($id)
    {
        // Récupérer le vol via id
        $vol = $this->volModel->findById($id);

        // Récupérer l'avion associé
        $avion = $this->avionModel->findById($vol["IdAvion"]);

        // Récupérer les aéroports du vol
        $aeroportDepart = $this->aeroportModel->findById($vol["IdAeroportDepart"]);
        $aeroportArrivee = $this->aeroportModel->findById($vol["IdAeroportArrivee"]);

        // Envoi à la vue
        $this->render("admin/vol/show", [
            "vol"             => $vol,
            "avion"           => $avion,
            "aeroportDepart"  => $aeroportDepart,
            "aeroportArrivee" => $aeroportArrivee
        ], "layout_admin");
    }

    // Formulaire d'édition
    public function edit($id)
    {
        try {
            // Récupérer le vol via id
            $vol = $this->volModel->findById($id);

            // Empêcher la modification du vol s'il est Annulé/En Cours/Arrivé
            if ($vol["StatutVol"] !== "Planifie") {
                throw new \Exception("Ce vol ne peut plus être modifié car il n'est plus en statut 'Planifie'.");
            }

            // Lister tous les avions
            $avions = $this->avionModel->findAll();

            // Lister tous les aéroports
            $aeroports = $this->aeroportModel->findAll();

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Envoi à la vue
        $this->render("admin/vol/edit", [
            "vol" => $vol,
            "avions" => $avions,
            "aeroports" => $aeroports
        ], "layout_admin");
    }

    // Mise à jour du vol
    public function update($id)
    {
        try {
            $depart = $_POST["DateHeureDepartUTC"];
            $arrivee = $_POST["DateHeureArriveeUTC"];
            $idAvion = $_POST["IdAvion"];
            $idAeroportDepart = $_POST["IdAeroportDepart"];
            $idAeroportArrivee = $_POST["IdAeroportArrivee"];
            $idVolAller = $_POST["IdVolAller"];
            $idVolRetour = $_POST["IdVolRetour"];
            $now = date("Y-m-d H:i:s");

            // Vérifier que le vol existe
            $vol = $this->volModel->findById($id);
            if (!$vol) {
                throw new \Exception("Vol introuvable.");
            }

            // Vérifier que l'avion existe
            $avion = $this->avionModel->findById($idAvion);
            if (!$avion) {
                throw new \Exception("Avion introuvable.");
            }

            // Vérifier que l'avion n'est pas indisponible
            if ($avion["StatutAvion"] === "Maintenance" || $avion["StatutAvion"] === "HorsService") {
                throw new \Exception("Impossible de modifier ce vol : l’avion est indisponible.");
            }

            // Vérifier les dates
            if (strtotime($depart) < strtotime($now)) {
                throw new \Exception("Erreur : la date de départ ne peut pas être dans le passé.");
            }

            if (strtotime($arrivee) < strtotime($now)) {
                throw new \Exception("Erreur : la date d'arrivée ne peut pas être dans le passé.");
            }

            if (strtotime($arrivee) <= strtotime($depart)) {
                throw new \Exception("Erreur : la date d'arrivée doit être strictement supérieure à la date de départ.");
            }

            // Vérifier que l'avion n'est pas occupé sur un autre vol
            if ($this->volModel->avionOccupe($idAvion, $depart, $arrivee, $id)) {
                throw new \Exception("Impossible de modifier ce vol : l’avion est déjà utilisé sur cette période.");
            }

            // Vérifier où se trouvera l’avion juste avant ce vol
            $dernierVol = $this->volModel->getDernierVolAvant($idAvion, $depart, $id);

            // Si le vol est futur, on ignore la position actuelle
            if (strtotime($depart) > time()) {
                // L’avion sera au bon aéroport au moment du départ
                $avion["IdAeroportActuel"] = $idAeroportDepart;
            }

            if ($dernierVol) {
                $aeroportFinal = $dernierVol["IdAeroportArrivee"];

                if ($aeroportFinal != $idAeroportDepart) {
                    throw new \Exception("Impossible de modifier ce vol : l’avion sera dans un autre aéroport avant ce vol.");
                }
            } else {
                // Aucun vol avant : position actuelle
                if ($avion["IdAeroportActuel"] != $idAeroportDepart) {
                    throw new \Exception("Impossible de modifier ce vol : l’avion ne se trouve pas dans cet aéroport.");
                }
            }

            // Récupérer les aéroports via id
            $aeroportDepart = $this->aeroportModel->findById($idAeroportDepart);
            $aeroportArrivee = $this->aeroportModel->findById($idAeroportArrivee);

            // Vérifier compatibilité des aéroports
            if ($avion["LongueurAvion"] > $aeroportDepart["LongueurAvionMax"]) {
                throw new \Exception("L'aéroport de départ ne peut pas accueillir cet avion.");
            }

            if ($avion["LongueurAvion"] > $aeroportArrivee["LongueurAvionMax"]) {
                throw new \Exception("L'aéroport d'arrivée ne peut pas accueillir cet avion.");
            }

            // Mise à jour du vol
            $data = [
                "NumeroVol"           => $_POST["NumeroVol"],
                "DateHeureDepartUTC"  => $depart,
                "DateHeureArriveeUTC" => $arrivee,
                "StatutVol"           => $_POST["StatutVol"],
                "IdAvion"             => $idAvion,
                "IdAeroportDepart"    => $idAeroportDepart,
                "IdAeroportArrivee"   => $idAeroportArrivee
            ];

            // Mise à jour du vol
            $this->volModel->update($id, $data);

            // Mise à jour de la position de l’avion si le vol est terminé
            if ($_POST["StatutVol"] === "Arrive") {
                $this->avionModel->update($idAvion, [
                    "IdAeroportActuel" => $idAeroportArrivee
                ]);
            }

            // Annuler les réservations du vol si vol annulé
            if ($_POST["StatutVol"] === "Annule") {
                $this->reservationModel->annulerReservationsDuVol($id);
            }
            
            Flash::add('success', "Vol mis à jour avec succès"); 

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Redirection
        $this->redirect("/admin/vol");
    }

    // Suppression du vol
    public function delete($id)
    {
        try {
            $this->volModel->delete($id);
        } catch (\PDOException $e) {
            die("Impossible de supprimer ce vol.");
        }

        // Redirection
        $this->redirect("/admin/vol");
    }

    // Liste des passagers d'un vol
    public function passagers($idVol)
    {
        // Récupérer le vol via id
        $vol = $this->volModel->findById($idVol);
        
        // Récupérer les passagers du vol
        $passagers = $this->volModel->getPassagersByVol($idVol);

        // Envoi à la vue
        $this->render("admin/vol/passagers", [
            "vol" => $vol,
            "passagers" => $passagers
        ], "layout_admin");
    }

    // Faire décoller un vol    
    public function decoller($idVol)
    {
        // Mettre le vol en EnCours
        $this->volModel->update($idVol, [
            "StatutVol" => "EnCours"
        ]);

        // LOG
        $this->logCustom
        (
            "VOL",
            "Décollage du vol $idVol",
            ["idVol" => $idVol]
        );

        // Confirmer les réservations du vol
        $this->reservationModel->confirmerReservationsDuVol($idVol);

        // Redirection
        header("Location: /admin/vol");
        exit;
    }

    // Faire atterrir un vol
    public function atterrir($idVol)
    {
        // Mettre le vol en Arrive
        $this->volModel->update($idVol, [
            "StatutVol" => "Arrive"
        ]);

        // LOG
        $this->logCustom
        (
            "VOL",
            "Atterrissage du vol $idVol",
            ["idVol" => $idVol]
        );

        // Cloturer les réservations du vol
        $this->reservationModel->cloturerReservationsDuVol($idVol);

        // Redirection
        header("Location: /admin/vol");
        exit;
    }

}