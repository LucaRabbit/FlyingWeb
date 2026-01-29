<?php
namespace App\Controllers\admin;

use App\Core\Controller;
use App\Models\ReservationModel;
use App\Models\VolModel;

use App\Helpers\Flash;

use Exception;

class ReservationController extends Controller
{
    private ReservationModel $reservationModel;
    private VolModel $volModel;

    public function __construct()
    {

        // Accès réservé aux admins connectés
        if (empty($_SESSION["admin_logged"])) {
            header("Location: /admin/login");
            exit;
        }

        $this->reservationModel = new ReservationModel();
        $this->volModel = new VolModel();
    }

    // Liste des réservations
    public function index()
    {
        // récupérer toutes les réservations
        $reservations = $this->reservationModel->findAll();

        foreach ($reservations as &$r) {

            // Récupérer les vols Aller et Retour
            $volAller = $this->reservationModel->getVolAller($r["IdReservation"]);
            $volRetour = $this->reservationModel->getVolRetour($r["IdReservation"]);

            $r["VolAller"] = $volAller;
            $r["VolRetour"] = $volRetour;

            // Vérifier si modifiable
            $r["modifiable"] = $this->reservationModel->reservationModifiable($r);
        }

        // Envoi à la vue
        $this->render("admin/reservation/index", [
            "reservations" => $reservations
        ], "layout_admin");
    }

    // Afficher une réservation
    public function show($id)
    {
        // Récupérer la réservation via id
        $reservation = $this->reservationModel->findById($id);

        // Récupérer les vols associés
        $volAller = $this->reservationModel->getVolAller($id);
        $volRetour = $this->reservationModel->getVolRetour($id);

        // Récupérer les passagers
        $passagers = $this->reservationModel->getPassagers($id);

        // Vérifier si modifiable
        $modifiable = $this->reservationModel->reservationModifiable($reservation);

        // Envoi à la vue
        $this->render("admin/reservation/show", [
            "reservation" => $reservation,
            "volAller" => $volAller,
            "volRetour" => $volRetour,
            "passagers" => $passagers,
            "modifiable" => $modifiable
        ], "layout_admin");
    }

    // Formulaire d'édition
    public function edit($id)
    {
        try {
            // Récupérer la réservation via id
            $reservation = $this->reservationModel->findById($id);

            // Récupérer les vols associés
            $vols = $this->volModel->findAll();

            // Récupérer le vol Aller
            $volAller = $this->reservationModel->getVolAller($id);

            if ($volAller["StatutVol"] !== "Planifie") {
                throw new \Exception("Impossible de modifier cette réservation : le vol n'est plus modifiable.");
            }

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Envoi à la vue
        $this->render("admin/reservation/edit", [
            "reservation" => $reservation,
            "vols" => $vols,
            "volAller" => $volAller
        ], "layout_admin");
    }

    // Mise à jour
    public function update($id)
    {
        // Récupérer le vol Aller
        $volAller = $this->reservationModel->getVolAller($id);

        // Empêcher la modification de la réservation si le vol associé est Annulé/En Cours/Arrivé
        if ($volAller["StatutVol"] !== "Planifie") {
            die("Impossible de modifier cette réservation : le vol n'est plus modifiable.");
        }

        $data = [
            "DateReservation"    => $_POST["DateReservation"],
            "NbPassagersReserve" => $_POST["NbPassagersReserve"],
            "TokenLien"          => $_POST["TokenLien"],
            "EmailReservant"     => $_POST["EmailReservant"],
            "StatutReservation"  => $_POST["StatutReservation"]
        ];

        // Mise à  jour de la réservation
        $this->reservationModel->update($id, $data);

        // Redirection
        $this->redirect("/admin/reservation");
    }

    // Formulaire d'ajout de passager
    public function addPassagerForm($id)
    {
        try {
            // Récuperer la réservation via id
            $reservation = $this->reservationModel->findById($id);

            if (!$reservation) {
                throw new \Exception("Réservation introuvable.");
            }

            // Empêcher l'ajout de passager si la réservation n'est pas modifiable
            if (!$this->reservationModel->reservationModifiable($reservation)) {
                throw new \Exception("Impossible d'ajouter un passager : la réservation ou le vol n'est plus modifiable.");
            }

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Envoi de la vue
        $this->render("admin/reservation/add_passager", [
            "reservation" => $reservation
        ]);
    }

    // Traitement ajout passager
    public function addPassager($id)
    {
        try {
            // Récuperer la réservation via id
            $reservation = $this->reservationModel->findById($id);

            if (!$this->reservationModel->reservationModifiable($reservation)) {
                throw new \Exception("Impossible d'ajouter un passager : la réservation ou le vol n'est plus modifiable.");
            }

            // Récupérer l'ID réel de la réservation
            $idReservation = $reservation["IdReservation"];

            $volAller = $this->volModel->findById($reservation["IdVolAller"]);
            $volRetour = $reservation["IdVolRetour"] ? $this->volModel->findById($reservation["IdVolRetour"]) : null;

            // Vérifier places restantes
            if ($this->reservationModel->isVolComplet($volAller["IdVol"])) {
                throw new \Exception("Impossible d'ajouter un passager : le vol aller est complet.");
            }

            if ($volRetour && $this->reservationModel->isVolComplet($volRetour["IdVol"])) {
                throw new \Exception("Impossible d'ajouter un passager : le vol retour est complet.");
            }

            $nom = $_POST["Nom"];
            $prenom = $_POST["Prenom"];
            $siege = $_POST["NumeroSiege"];

            // Ajout du passager à la réservation
            $idPassager = $this->reservationModel->addPassager($nom, $prenom);

            // Ajout sur le vol aller
            $volAller = $this->reservationModel->getVolAller($id);
            $this->reservationModel->attachPassagerToReservation(
                $id,
                $idPassager,
                $siege,
                $volAller["IdVol"]
            );

            // Ajout sur le vol retour si existe
            $volRetour = $this->reservationModel->getVolRetour($id);
            if ($volRetour) {

                // Attacher le passager à la réservation
                $this->reservationModel->attachPassagerToReservation(
                    $id,
                    $idPassager,
                    null,
                    $volRetour["IdVol"]
                );
            }

            // Recalcul du nombre de passagers
            $this->reservationModel->recalcPassagerCount($idReservation);

            Flash::add('success', "Passager ajouté avec succès.");

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Redirection
        $this->redirect("/admin/reservation/show/$id");
    }

    // Suppression d'un passager
    public function removePassager($id, $idPassager)
    {
        try {
            // Récupération de la réservation via id
            $reservation = $this->reservationModel->findById($id);

            // Empêcher la suppression de passagers si la réservation n'est plus modifiable
            if (!$this->reservationModel->reservationModifiable($reservation)) {
                throw new \Exception("Impossible de supprimer un passager : la réservation ou le vol n'est plus modifiable.");
            }

            // Récupérer l'ID réel de la réservation
            $idReservation = $reservation["IdReservation"];

            // Compter les passagers uniques
            $nbPassagers = $this->reservationModel->countUniquePassagers($id);

            // EMpêcher la suppression du dernier passager
            if ($nbPassagers <= 1) {
                throw new \Exception("Impossible de supprimer le dernier passager d'une réservation.");
            }

            // Suppression du passager
            $this->reservationModel->detachPassagerFromReservation($id, $idPassager);

            // Recalcul du nombre de passagers
            $this->reservationModel->recalcPassagerCount($idReservation);
            
            Flash::add('success', "Passager supprimé avec succès.");

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Redirection
        $this->redirect("/admin/reservation/show/$id");
    }

    // Annulation de la réservation
    public function cancel($id)
    {
        try {
            // Récupération de la réservation via id
            $reservation = $this->reservationModel->findById($id);

            // Empêcher la suppression de la réservation si elle n'est plsu modifiable
            if (!$this->reservationModel->reservationModifiable($reservation)) {
                throw new \Exception("Impossible d'annuler cette réservation : le vol n'est plus modifiable.");
            }

            // Annulation de la réservation
            $this->reservationModel->cancelReservation($id);
            Flash::add('success', "Réservation annulée avec succès.");

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Redirection
        $this->redirect("/admin/reservation");
    }
}