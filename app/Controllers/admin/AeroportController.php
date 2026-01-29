<?php
namespace App\Controllers\admin;

use App\Core\Controller;
use App\Models\AeroportModel;
use App\Models\VolModel;
use App\Models\AvionModel;

use App\Helpers\Flash;
use Exception;

class AeroportController extends Controller
{
    private AeroportModel $aeroportModel;
    private VolModel $volModel;
    private AvionModel $avionModel;

    public function __construct()
    {
        // Accès réservé aux admins connectés
        if (empty($_SESSION["admin_logged"])) {
            header("Location: /admin/login");
            exit;
        }

        // Charger les modeles
        $this->aeroportModel = new AeroportModel();
        $this->volModel = new VolModel();
        $this->avionModel = new AvionModel();

    }

    // Liste des aéroports
    public function index()
    {
        // Charger la liste des aéroports
        $aeroports = $this->aeroportModel->findAll();

        foreach ($aeroports as &$aeroport) {
            $id = $aeroport["IdAeroport"];

            // Avions stationnés ?
            $aeroport["AvionsStationnes"] = $this->avionModel->avionsStationnesDansAeroport($id);

            // Utilisé dans un vol futur ?
            $aeroport["UtiliseDansVolFutur"] = $this->volModel->aeroportUtiliseDansUnVolFutur($id);

            // Vérifier si modifiable
            $aeroport["modifiable"] = !$this->volModel->aeroportUtiliseDansUnVolFutur($id)
                           && !$this->avionModel->avionsStationnesDansAeroport($id);
        }

        $this->render("admin/aeroport/index", [
            "aeroports" => $aeroports
        ], "layout_admin");
    }

    // Formulaire de création
    public function create()
    {
        // Envoi à la vue
        $this->render("admin/aeroport/create", [], "layout_admin");
    }

    // Affichage d'un aéroport
    public function show($id)
    {
        // Récupérer un aéroport via id
        $aeroport = $this->aeroportModel->findById($id);

        // Récupérer les vols associés
        $vols = $this->volModel->findByAeroport($id);

        // Récupérer les avions stationnés
        $avions = $this->avionModel->findByAeroport($id);

        // Vérifier si des avions sont stationnés dans cet aéroport
        $aeroport["AvionsStationnes"] = $this->avionModel->avionsStationnesDansAeroport($id);

        // Vérifier si cet aéroport est utilisé dans un vol futur
        $aeroport["UtiliseDansVolFutur"] = $this->volModel->aeroportUtiliseDansUnVolFutur($id);

        // Vérifier si modifiable
        $aeroport["modifiable"] = !$this->volModel->aeroportUtiliseDansUnVolFutur($id)
                                && !$this->avionModel->avionsStationnesDansAeroport($id);

        // Envoi à la vue
        $this->render("admin/aeroport/show", [
            "aeroport" => $aeroport,
            "vols" => $vols,
            "avions" => $avions
        ], "layout_admin");
    }

    // Enregistrement
    public function store()
    {
        $data = [
            "CodeIATA"         => $_POST["CodeIATA"],
            "NomOfficiel"      => $_POST["NomOfficiel"],
            "Ville"            => $_POST["Ville"],
            "Pays"             => $_POST["Pays"],
            "LongueurAvionMax" => $_POST["LongueurAvionMax"]
        ];

        $this->aeroportModel->create($data);

        $this->redirect("/admin/aeroport");
    }

    // Formulaire d'édition
    public function edit($id)
    {
        try {
            // Récupérer l'aéroport par id
            $aeroport = $this->aeroportModel->findById($id);

            if ($this->volModel->aeroportUtiliseDansUnVolFutur($id)) {
                throw new \Exception("Impossible de modifier cet aeroport : il est utilisé dans un ou plusieurs vols.");
            }

                // Empêcher de réduire la longueur max si des avions sont stationnés
            if ($this->avionModel->avionsStationnesDansAeroport($id)) {

                if ($_POST["LongueurAvionMax"] < $aeroport["LongueurAvionMax"]) {
                    throw new \Exception("Impossible de réduire la capacité maximale : des avions sont stationnés dans cet aéroport.");
                }
            }

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Envoi à la vue
        $this->render("admin/aeroport/edit", ["aeroport" => $aeroport], "layout_admin");
    }

    // Mise à jour
    public function update($id)
    {
        try {
            // Récupérer l'aéroport par id
            $aeroport = $this->aeroportModel->findById($id);

            // Empêcher la modification si l'aéroport est utilisé dans un vol futur
            if ($this->volModel->aeroportUtiliseDansUnVolFutur($id)) {
                throw new \Exception("Impossible de modifier cet aeroport : il est utilisé dans un ou plusieurs vols.");
            }

            $data = [
                "CodeIATA"         => $_POST["CodeIATA"],
                "NomOfficiel"      => $_POST["NomOfficiel"],
                "Ville"            => $_POST["Ville"],
                "Pays"             => $_POST["Pays"],
                "LongueurAvionMax" => $_POST["LongueurAvionMax"]
            ];

            // Mise à jour de l'aéroport
            $this->aeroportModel->update($id, $data);
            Flash::add('success', "Aéroport mis à jour avec succès");

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Redirection
        $this->redirect("/admin/aeroport");
    }

    // Suppression
    public function delete($id)
    {
        try {
            // Récupérer l'aéroport par id
            $aeroport = $this->aeroportModel->findById($id);

            // Empêcher la suppression si un avion est stationné dans l'aéroport
            if ($this->avionModel->avionsStationnesDansAeroport($id)) {
                throw new \Exception("Impossible de supprimer cet aéroport : un ou plusieurs avions y sont stationnés.");
            }
            // Empêcher la suppression si l'aéroport est utilisé dans un vol futur
            if ($this->volModel->aeroportUtiliseDansUnVolFutur($id)) {
                throw new \Exception("Impossible de supprimer cet aeroport : il est utilisé dans un ou plusieurs vols.");
            }

            try {
                // Suppression de l'aéroport
                $this->aeroportModel->delete($id);
            } catch (\PDOException $e) {
                die("Erreur lors de la suppression de l'aeroport.");
            }

            Flash::add('success', "Aéroport supprimé avec succès");

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Redirection
        $this->redirect("/admin/aeroport");
    }
}