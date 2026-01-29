<?php
namespace App\Controllers\front;

use App\Core\Controller;
use App\Models\AeroportModel;
use App\Models\VolModel;
use App\Models\ReservationModel;

use App\Helpers\Flash;

use Exception;

class FrontVolController extends Controller
{
    private AeroportModel $aeroportModel;
    private VolModel $volModel;
    private ReservationModel $reservationModel;


    public function __construct()
    {
        $this->aeroportModel = new AeroportModel();
        $this->volModel = new VolModel();
        $this->reservationModel = new ReservationModel();

    }

    // Page du moteur de recherche
    public function index()
    {
        // Récupérer les villes disponibles
        $villes = $this->aeroportModel->getAllVilles();

        // Valeurs par défaut pour pré-remplir le formulaire
        $villeDepart = "";
        $villeArrivee = "";
        $dateDepart = "";
        $tolerance = 0;
        
        $volsAller = [];

        // Afficher tous les vols à venir
        if ($_SERVER["REQUEST_METHOD"] === "GET") {

            // Récupérer tous les vols à venir
            $volsAller = $this->volModel->findAllWithAeroports();

            // Ajouter les infos de disponibilité
            foreach ($volsAller as &$vol) {
                $placesRestantes = $this->reservationModel->getPlacesRestantes($vol["IdVol"]);
                $vol["PlacesRestantes"] = $placesRestantes;
                $vol["Complet"] = ($placesRestantes <= 0);
            }
        }

        // Filtrer les vols
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $villeDepart = $_POST["villeDepart"];
            $villeArrivee = $_POST["villeArrivee"];
            $dateDepart = $_POST["dateDepart"];
            $tolerance = intval($_POST["tolerance"]);

            // Récupérer les aéroports via les villes
            $aeroportsDepart = $this->aeroportModel->findByVille($villeDepart);
            $aeroportsArrivee = $this->aeroportModel->findByVille($villeArrivee);

            if (!empty($aeroportsDepart) && !empty($aeroportsArrivee)) {

                $dateMin = date("Y-m-d", strtotime("$dateDepart -$tolerance days"));
                $dateMax = date("Y-m-d", strtotime("$dateDepart +$tolerance days"));

                // Recherche filtrée
                $volsAller = $this->volModel->searchVolsPlage(
                    $aeroportsDepart,
                    $aeroportsArrivee,
                    $dateMin,
                    $dateMax
                );

                // Ajouter les infos de disponibilité
                foreach ($volsAller as &$vol) {
                    $placesRestantes = $this->reservationModel->getPlacesRestantes($vol["IdVol"]);
                    $vol["PlacesRestantes"] = $placesRestantes;
                    $vol["Complet"] = ($placesRestantes <= 0);
                }

            } else {
                // Aucun résultat
                $volsAller = [];
            }
        }

        // Envoyer à la vue
        $this->render("front/vol/recherche", [
            "volsAller"    => $volsAller,
            "villeDepart"  => $villeDepart,
            "villeArrivee" => $villeArrivee,
            "dateDepart"   => $dateDepart,
            "tolerance"    => $tolerance,
            "villes"       => $villes
        ], "layout_front");
    }

    // Proposer les vols retour
    public function retour($idVolAller)
    {
        try {
            // Récupération du vol aller
            $volAller = $this->volModel->findByIdWithAeroports($idVolAller);

            if (!$volAller) {
                throw new \Exception("Vol introuvable.");
            }

            // Date d’arrivée du vol aller
            $dateMin = substr($volAller["DateHeureArriveeUTC"], 0, 10);

            // Pas de limite haute réelle
            $dateMax = "2100-01-01";

            // Aéroports inversés pour le retour
            $aeroportDepartRetour = [$this->aeroportModel->findById($volAller["IdAeroportArrivee"])];
            $aeroportArriveeRetour = [$this->aeroportModel->findById($volAller["IdAeroportDepart"])];

            // Recherche des vols retour
            $volsRetour = $this->volModel->searchVolsPlage(
                $aeroportDepartRetour,
                $aeroportArriveeRetour,
                $dateMin,
                $dateMax
            );

            // Ajouter les infos de disponibilité
            foreach ($volsRetour as &$vol) {
                $placesRestantes = $this->reservationModel->getPlacesRestantes($vol["IdVol"]);
                $vol["PlacesRestantes"] = $placesRestantes;
                $vol["Complet"] = ($placesRestantes <= 0);
            }

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Envoi à la vue
        $this->render("front/vol/retour", [
            "volAller"   => $volAller,
            "volsRetour" => $volsRetour
        ], "layout_front");
    }

    // Choisir Aller simple
    public function terminerSansRetour($idVolAller)
    {
        // Récupération du vol aller avec noms d’aéroports
        $volAller = $this->volModel->findByIdWithAeroports($idVolAller);

        // Envoi à la vue
        $this->render("front/vol/confirmation", [
            "volAller"  => $volAller,
            "volRetour" => null
        ], "layout_front");
    }

    // Choisir Aller + Retour
    public function terminerAvecRetour($idVolAller, $idVolRetour)
    {
        // Récupération des vols avec noms d’aéroports
        $volAller  = $this->volModel->findByIdWithAeroports($idVolAller);
        $volRetour = $this->volModel->findByIdWithAeroports($idVolRetour);

        // Envoi à la vue
        $this->render("front/vol/confirmation", [
            "volAller"  => $volAller,
            "volRetour" => $volRetour
        ], "layout_front");
    }
}