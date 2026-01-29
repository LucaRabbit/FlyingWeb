<?php
namespace App\Controllers\admin;

use App\Core\Controller;
use App\Models\AvionModel;
use App\Models\AeroportModel;
use App\Models\VolModel;

use App\Helpers\Flash;
use Exception;

class AvionController extends Controller
{
    private AvionModel $avionModel;
    private AeroportModel $aeroportModel;
    private VolModel $volModel;

    public function __construct()
    {

        // Accès réservé aux admins connectés
        if (empty($_SESSION["admin_logged"])) {
            header("Location: /admin/login");
            exit;
        }

        $this->avionModel = new AvionModel();
        $this->aeroportModel = new AeroportModel();
        $this->volModel = new VolModel();
    }

    // Liste des avions
    public function index()
    {
        // Récupérer tous les avions
        $avions = $this->avionModel->findAll();

        // Ajouter le nom de l'aéroport de stationnement de chaque avion
        foreach ($avions as &$a) {
            if ($a["IdAeroportActuel"] !== null) {

                // Récupérer l'aéroport de stationnement de l'avion
                $aeroport = $this->aeroportModel->findById($a["IdAeroportActuel"]);
                
                $a["NomAeroportActuel"] = $aeroport["NomAeroport"];
            } else {
                $a["NomAeroportActuel"] = "En vol";
            }

            $idAvion = $a["IdAvion"];

            // Vérifier si modifiable
            $a["modifiable"] = $this->volModel->avionModifiable($a["IdAvion"]);
        }

        // Envoi à la vue
        $this->render("admin/avion/index", ["avions" => $avions], "layout_admin");
    }

    // Formulaire de création
    public function create()
    {
        // Charger la liste des aéroports
        $aeroports = $this->aeroportModel->findAll();

        // Envoi à la vue
        $this->render("admin/avion/create", [
            "aeroports" => $aeroports
        ], "layout_admin");
    }

    // Enregistrement
    public function store()
    {
        $data = [
            "Immatriculation"   => $_POST["Immatriculation"],
            "Modele"            => $_POST["Modele"],
            "NbPlacesPassager"  => $_POST["NbPlacesPassager"],
            "LongueurAvion"     => $_POST["LongueurAvion"],
            "StatutAvion"       => $_POST["StatutAvion"],
            "IdAeroportActuel"  => $_POST["IdAeroportActuel"]
        ];

        // Création de l'avion
        $this->avionModel->create($data);

        // Redirection
        $this->redirect("/admin/avion");
    }

    // Affichage d'un avion
    public function show($id)
    {
        try {
            // Récupérer l'avion via id
            $avion = $this->avionModel->findById($id);
            if (!$avion) {
                throw new \Exception("Avion introuvable.");
            }

            // Récupérer tous les vols de cet avion
            $vols = $this->volModel->findByAvion($id);

            // Vérifier si modifiable
            $avion["modifiable"] = $this->volModel->avionModifiable($avion["IdAvion"]);

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Envoi à la vue
        $this->render("admin/avion/show", [
            "avion" => $avion,
            "vols"  => $vols
        ], "layout_admin");
    }

    // Formulaire d'édition
    public function edit($id)
    {
        try {
        // Récupérer l'avion via id
        $avion = $this->avionModel->findById($id);

        if (!$avion) {
            throw new \Exception("Avion introuvable.");
        }

        // Empêcher la modification si l'avion est utilisé dans un vol futur
        if ($this->volModel->avionUtiliseDansUnVolFutur($id)) {
            throw new \Exception("Impossible de modifier cet avion : il est utilisé dans un vol futur.");
        }

        // Empêcher la modification si l'avion est en vol
        if ($avion["StatutAvion"] === "EnVol") {
            throw new \Exception("Impossible de modifier cet avion : il est actuellement en vol.");
        }

        // Charger la liste des aéroports
        $aeroports = $this->aeroportModel->findAll();

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Envoi à la vue
        $this->render("admin/avion/edit", [
            "avion" => $avion,
            "aeroports" => $aeroports
        ], "layout_admin");
    }

    // Mise à jour
    public function update($id)
    {
        try {
        // Récupérer l'avion via id
        $avion = $this->avionModel->findById($id);

        if (!$avion) {
            throw new \Exception("Avion introuvable.");
        }

        // Empêcher la modification si l'avion est utilisé dans un vol futur
        if ($this->volModel->avionUtiliseDansUnVolFutur($id)) {
            throw new \Exception("Impossible de modifier cet avion : il est utilisé dans un vol futur.");
        }

        // Vérifier la capacité de l'aéroport
        $idAeroport = $_POST["IdAeroportActuel"];

        if (!empty($idAeroport)) {
            $aeroport = $this->aeroportModel->findById($idAeroport);

            // Empêcher la modification de la longueur de l'avion
            if ($aeroport["LongueurAvionMax"] < $_POST["LongueurAvion"]) {
                throw new \Exception("Impossible de placer cet avion dans cet aéroport : sa taille dépasse la capacité maximale.");
            }
        }

        $data = [
            "Immatriculation" => $_POST["Immatriculation"],
            "Modele" => $_POST["Modele"],
            "NbPlacesPassager" => $_POST["NbPlacesPassager"],
            "LongueurAvion" => $_POST["LongueurAvion"],
            "StatutAvion" => $_POST["StatutAvion"],
            "IdAeroportActuel" => $idAeroport
        ];

        // Mise à jour de l'avion
        $this->avionModel->update($id, $data);

        Flash::add('success', "Avion mis à jour avec succès");

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Redirection
        $this->redirect("/admin/avion");
    }

    // Suppression
    public function delete($id)
    {
        try {

        // Récupérer l'avion via id
        $avion = $this->avionModel->findById($id);

        if (!$avion) {
            throw new \Exception("Avion introuvable.");
        }

        // Empêcher la suppression d'un avion en vol
        if ($avion["StatutAvion"] === "EnVol") {
            throw new \Exception("Impossible de supprimer cet avion : il est actuellement en vol.");
        }

        // Empêcher la suppression si l'avion est utilisé dans un vol futur
        if ($this->volModel->avionUtiliseDansUnVolFutur($id)) {
            throw new \Exception("Impossible de supprimer cet avion : il est utilisé dans un vol futur.");
        }

        try {
            // Suppression de l'avion
            $this->avionModel->delete($id);
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la suppression de l'avion.");
        }

        Flash::add('success', "Avion supprimé avec succès");

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Redirection
        $this->redirect("/admin/avion");
    }
}