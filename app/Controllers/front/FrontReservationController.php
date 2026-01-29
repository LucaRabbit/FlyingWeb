<?php
namespace App\Controllers\front;

use App\Core\Controller;
use App\Models\ReservationModel;
use App\Models\VolModel;
use App\Models\AeroportModel;

use App\Helpers\Flash;

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// PDF et QR Code
use TCPDF;

class FrontReservationController extends Controller
{
    private ReservationModel $reservationModel;
    private VolModel $volModel;
    private AeroportModel $aeroportModel;

    public function __construct()
    {
        $this->reservationModel = new ReservationModel();
        $this->volModel = new VolModel();
        $this->aeroportModel = new AeroportModel();
    }

    // Formulaire infos réservant
    public function infosForm()
    {
        try {
            $idVolAller = $_GET["aller"];
            $idVolRetour = $_GET["retour"] ?? null;

            // Charger les vols avec noms d’aéroports
            $volAller = $this->volModel->findByIdWithAeroports($idVolAller);
            $volRetour = $idVolRetour ? $this->volModel->findByIdWithAeroports($idVolRetour) : null;

            if (!$volAller) {
                throw new \Exception("Vol aller introuvable.");
            }

            // Places restantes
            $placesAller = $this->reservationModel->getPlacesRestantes($idVolAller);
            $placesRetour = $idVolRetour ? $this->reservationModel->getPlacesRestantes($idVolRetour) : null;
            
        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Envoi à la vue
        $this->render("front/reservation/infos", [
            "idVolAller"   => $idVolAller,
            "idVolRetour"  => $idVolRetour,
            "volAller"     => $volAller,
            "volRetour"    => $volRetour,
            "placesAller"  => $placesAller,
            "placesRetour" => $placesRetour
        ], "layout_front");
    }

    // Sauvegarder les informations du réservant
    public function saveInfosReservant()
    {
        try {
            $nbPassagers = (int) $_POST["NbPassagersReserve"];
            $idVolAller = $_POST["IdVolAller"];
            $idVolRetour = $_POST["IdVolRetour"] ?? null;

            // Vérification places restantes vol aller
            $placesAller = $this->reservationModel->getPlacesRestantes($idVolAller);

            if ($nbPassagers > $placesAller) {
                throw new \Exception("Il ne reste que $placesAller place(s) sur le vol aller. Impossible de réserver $nbPassagers.");
            }

            // Vérification places restantes vol retour
            if (!empty($idVolRetour)) {
                $placesRetour = $this->reservationModel->getPlacesRestantes($idVolRetour);
                if ($nbPassagers > $placesRetour) {
                    throw new \Exception("Il ne reste que $placesRetour place(s) sur le vol retour. Impossible de réserver $nbPassagers.");
                }
            }

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Stockage temporaire
        $_SESSION["reservation_temp"] = [
            "EmailReservant"      => $_POST["EmailReservant"],
            "NbPassagersReserve"  => $nbPassagers,
            "IdVolAller"          => $idVolAller,
            "IdVolRetour"         => $idVolRetour,
            "Passagers"           => []
        ];

        // Redirection
        header("Location: /reservation/passagers");
        exit;
    }

    // Formulaire passagers
    public function passagersForm()
    {
        if (!isset($_SESSION["reservation_temp"])) {
            // L'utilisateur arrive ici sans avoir rempli les infos
            $this->redirect("/reservation/infos");
            exit;
        }

        $data = $_SESSION["reservation_temp"];

        // Envoi à la vue
        $this->render("front/reservation/passagers", [
            "reservation" => $data
        ], "layout_front");
    }

    // Sauvegarder temporairement les passagers
    public function savePassagers()
    {
        foreach ($_POST["Nom"] as $i => $nom) {
            $_SESSION["reservation_temp"]["Passagers"][] = [
                "Nom"    => $nom,
                "Prenom" => $_POST["Prenom"][$i],
                "Siege"  => null
            ];
        }

        header("Location: /reservation/recap");
        exit;
    }

    // Récapitulatif avant création de la réservation
    public function recap()
    {
        $data = $_SESSION["reservation_temp"];

        // Récupérer les vols associés
        $volAller = $this->volModel->findByIdWithAeroports($data["IdVolAller"]);
        $volRetour = $data["IdVolRetour"] ? $this->volModel->findByIdWithAeroports($data["IdVolRetour"]) : null;

        // Envoi à la vue
        $this->render("front/reservation/recap", [
            "reservation" => $data,
            "passagers"   => $data["Passagers"],
            "volAller"    => $volAller,
            "volRetour"   => $volRetour
        ], "layout_front");
    }

    // Confirmation : création de la réservation
    public function confirm()
    {
        try {
        $data = $_SESSION["reservation_temp"];

        // Empêcher la réservation si un des vols est complet
        if ($this->reservationModel->isVolComplet($data["IdVolAller"])) {
            throw new \Exception("Le vol aller est complet. Impossible de finaliser la réservation.");
        }

        if (!empty($data["IdVolRetour"]) && $this->reservationModel->isVolComplet($data["IdVolRetour"])) {
            throw new \Exception("Le vol retour est complet. Impossible de finaliser la réservation.");
        }

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Créer la réservation
        $idReservation = $this->reservationModel->create([
            "DateReservation"     => date("Y-m-d H:i:s"),
            "EmailReservant"      => $data["EmailReservant"],
            "NbPassagersReserve"  => $data["NbPassagersReserve"],
            "TokenLien"           => bin2hex(random_bytes(32)),
            "IdVolAller"          => $data["IdVolAller"],
            "IdVolRetour"         => !empty($data["IdVolRetour"]) ? $data["IdVolRetour"] : null
        ]);

        // Ajouter les passagers
        foreach ($data["Passagers"] as $p) {
            $idPassager = $this->reservationModel->addPassager($p["Nom"], $p["Prenom"]);

            // Associer au vol aller
            $this->reservationModel->attachPassagerToReservation(
                $idReservation,
                $idPassager,
                null,
                $data["IdVolAller"]
            );

            // Associer au vol retour si existe
            if ($data["IdVolRetour"]) {
                $this->reservationModel->attachPassagerToReservation(
                    $idReservation,
                    $idPassager,
                    null,
                    $data["IdVolRetour"]
                );
            }
        }

        // Récupérer les données pour l'email
        $reservation = $this->reservationModel->findById($idReservation);

        // Récupérer les vols
        $volAller = $this->volModel->findById($reservation["IdVolAller"]);
        $volRetour = $reservation["IdVolRetour"] ? $this->volModel->findById($reservation["IdVolRetour"]) : null;

        // Récupérer les aéroports du vol aller
        $aeroDepartAller = $this->aeroportModel->findById($volAller["IdAeroportDepart"]);
        $aeroArriveeAller = $this->aeroportModel->findById($volAller["IdAeroportArrivee"]);

        // Récupérer les aéroports du vol retour (si retour)
        $aeroDepartRetour = $volRetour ? $this->aeroportModel->findById($volRetour["IdAeroportDepart"]) : null;
        $aeroArriveeRetour = $volRetour ? $this->aeroportModel->findById($volRetour["IdAeroportArrivee"]) : null;

        // Passagers
        $passagers = $this->reservationModel->getPassagers($idReservation);

        // Envoyer l'email
        $this->sendConfirmationEmail([
            "reservation"       => $reservation,
            "volAller"          => $volAller,
            "volRetour"         => $volRetour,
            "aeroDepartAller"   => $aeroDepartAller,
            "aeroArriveeAller"  => $aeroArriveeAller,
            "aeroDepartRetour"  => $aeroDepartRetour,
            "aeroArriveeRetour" => $aeroArriveeRetour,
            "passagers"         => $passagers,
            "TokenLien"         => $reservation["TokenLien"]
        ]);

        // Nettoyer la session
        unset($_SESSION["reservation_temp"]);

        // Redirection
        header("Location: /reservation/recap-final/$idReservation");
        exit;
    }

    // Récapitulatif après création de la réservation
    public function recapFinal($idReservation)
    {
        try {
            // Récupérer la réservation
            $reservation = $this->reservationModel->findById($idReservation);

            if (!$reservation) {
                throw new \Exception("Réservation introuvable.");
            }

            // Récupérer les vols
            $volAller = $this->reservationModel->getVolAller($idReservation);
            $volRetour = $this->reservationModel->getVolRetour($idReservation);

            // Récupérer les aéroports du vol aller
            $aeroDepartAller = $this->aeroportModel->findById($volAller["IdAeroportDepart"]);
            $aeroArriveeAller = $this->aeroportModel->findById($volAller["IdAeroportArrivee"]);

            // Récupérer les aéroports du vol retour (si retour)
            $aeroDepartRetour = null;
            $aeroArriveeRetour = null;

            if ($volRetour) {
                $aeroDepartRetour = $this->aeroportModel->findById($volRetour["IdAeroportDepart"]);
                $aeroArriveeRetour = $this->aeroportModel->findById($volRetour["IdAeroportArrivee"]);
            }

            // Récupérer les passagers
            $passagers = $this->reservationModel->getPassagers($idReservation);

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Afficher la vue
        $this->render("front/reservation/recap_final", [
            "reservation"       => $reservation,
            "volAller"          => $volAller,
            "volRetour"         => $volRetour,
            "aeroDepartAller"   => $aeroDepartAller,
            "aeroArriveeAller"  => $aeroArriveeAller,
            "aeroDepartRetour"  => $aeroDepartRetour,
            "aeroArriveeRetour" => $aeroArriveeRetour,
            "passagers"         => $passagers
        ], "layout_front");
    }

    // Accéder à la réservation
    public function accesForm()
    {
        // Afficher la vue
        $this->render("front/reservation/acces", [], "layout_front");
    }

    // Voir la réservation
    public function voirReservation()
    {
        try {
        $token = $_GET['token'] ?? null;

        if (!$token) {
            throw new \Exception("Code de réservation manquant.");
        }

        // Récupérer la réservation via le token
        $reservation = $this->reservationModel->findByToken($token);

        if (!$reservation) {
            throw new \Exception("Aucune réservation trouvée avec ce code.");
        }

        $idReservation = $reservation["IdReservation"];

        // Récupérer les vols avec les noms des aéroports et des villes
        $volAller  = $this->volModel->findByIdWithAeroports(
            $this->reservationModel->getVolAller($idReservation)["IdVol"]
        );

        $volRetour = null;
        $volRetourData = $this->reservationModel->getVolRetour($idReservation);

        if ($volRetourData) {
            $volRetour = $this->volModel->findByIdWithAeroports($volRetourData["IdVol"]);
        }

        // Récupérer les passagers
        $passagers = $this->reservationModel->getPassagers($idReservation);

        // Vérifier si modifiable
        $modifiable = $this->reservationModel->reservationModifiable($reservation);

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }
        
        // Afficher la vue
        $this->render("front/reservation/show", [
            "reservation" => $reservation,
            "volAller"    => $volAller,
            "volRetour"   => $volRetour,
            "passagers"   => $passagers,
            "modifiable"  => $modifiable,
            "TokenLien"   => $token
        ], "layout_front");
    }

    // Configurer SMTP (PHPMailer)
    private function configureSMTP(PHPMailer $mail)
    {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER'];
        $mail->Password = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->CharSet = 'UTF-8';
    }

    // Envoyer l'email de confirmation + PDF avec QR Code
    private function sendConfirmationEmail(array $data)
    {
        $mail = new PHPMailer(true);

        try {
            // SMTP
            $this->configureSMTP($mail);

            $mail->setFrom($_ENV['SMTP_USER'], 'Flying Web - Service Réservations');
            $mail->addAddress($data["reservation"]["EmailReservant"]);
            $mail->Subject = "Votre confirmation de réservation #{$data['reservation']['IdReservation']}";
            $mail->isHTML(true);

            // URL du QR code
            $url = "http://flyingweb.local/reservation/show?token=" . $data["TokenLien"];

            // Variables pour les vues
            $dataForViews = $data;
            $dataForViews["qrCode"] = 'data:image/png;base64,' . base64_encode($qrPng);

            // Email HTML
            ob_start();
            extract($dataForViews);
            include __DIR__ . "/../../Views/emails/confirmation.php";
            $mail->Body = ob_get_clean();

            // PDF avec TCPDF
            require_once __DIR__ . '/../../../vendor/autoload.php';

            $pdf = new \TCPDF();
            $pdf->SetCreator('Flying Web');
            $pdf->SetAuthor('Flying Web');
            $pdf->SetTitle('Carte d’embarquement');

            $pdf->AddPage();

            // Charger le HTML PDF
            ob_start();
            extract($dataForViews);
            include __DIR__ . "/../../Views/pdf/carte_embarquement.php";
            $htmlPdf = ob_get_clean();

            // Afficher le HTML dans TCPDF
            $pdf->writeHTML($htmlPdf, true, false, true, false, '');

            // Ajouter le QR code
            $pdf->write2DBarcode($url, 'QRCODE,H', 150, 15, 40, 40);
            
            // Récupérer le PDF en binaire
            $pdfOutput = $pdf->Output('carte-embarquement.pdf', 'S');

            // Ajouter en pièce jointe
            $mail->addStringAttachment($pdfOutput, "carte-embarquement.pdf");

            // Envoi
            $mail->send();

        } catch (Exception $e) {
            error_log("Erreur email : " . $mail->ErrorInfo);
        }
    }

    // Formulaire d'ajout de passager
    public function addPassagerForm()
    {
        try {
            $token = $_GET['token'] ?? null;

            if (!$token) throw new \Exception("Token manquant.");

            // Récupération de la réservation via le token
            $reservation = $this->reservationModel->findByToken($token);

            if (!$reservation) {
                throw new \Exception("Réservation introuvable.");
            }

            // Vérifier si la réservation est encore modifiable
            if (!$this->reservationModel->reservationModifiable($reservation)) {
                throw new \Exception("Impossible : la réservation ou le vol n'est plus modifiable.");
            }

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Affichage du formulaire
        $this->render("front/reservation/add_passager", [
            "reservation" => $reservation,
            "token"       => $token
        ]);
    }

    // Traitement ajout passager
    public function addPassager()
    {
        try {
            $token = $_POST['token'] ?? null;

            if (!$token) throw new \Exception("Token manquant.");

            // Récupérer la réservation via id
            $reservation = $this->reservationModel->findByToken($token);

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

            // Récupération du formulaire
            $nom = $_POST["Nom"];
            $prenom = $_POST["Prenom"];

            // Création du passager
            $idPassager = $this->reservationModel->addPassager($nom, $prenom);

            // Ajout sur le vol aller
            $this->reservationModel->attachPassagerToReservation(
                $idReservation,
                $idPassager,
                null,
                $volAller["IdVol"]
            );

            // Ajout sur le vol retour (si existe)
            if ($volRetour) {
                $this->reservationModel->attachPassagerToReservation(
                    $idReservation,
                    $idPassager,
                    null,
                    $volRetour["IdVol"]
                );
            }

            // Recalcul du nombre de passagers
            $this->reservationModel->recalcPassagerCount($idReservation);

            Flash::add('success', "Passager ajouté avec succès");

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Redirection
        $this->redirect("/reservation/show?token=$token");
    }

    // Supprimer un passager
    public function removePassager()
    {
        try {
            $token = $_GET['token'] ?? null;
            $idPassager = $_GET['idPassager'] ?? null;

            // Récupération de la réservation via token
            $reservation = $this->reservationModel->findByToken($token);

            if (!$this->reservationModel->reservationModifiable($reservation)) {
                throw new \Exception("Impossible de supprimer un passager : la réservation ou le vol n'est plus modifiable.");
            }

            // Récupérer l'ID réel de la réservation
            $idReservation = $reservation["IdReservation"];

            // Compter les passagers uniques
            $nbPassagers = $this->reservationModel->countUniquePassagers($idReservation);

            if ($nbPassagers <= 1) {
                throw new \Exception("Impossible de supprimer le dernier passager d'une réservation.");
            }

            // Suppression du passager
            $this->reservationModel->detachPassagerFromReservation($idReservation, $idPassager);

            // Recalcul du nombre de passagers
            $this->reservationModel->recalcPassagerCount($idReservation);

            Flash::add('success', "Passager supprimé avec succès");

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }

        // Redirection
        $this->redirect("/reservation/show?token=$token");
    }

    // Annulation de la réservation
    public function cancel()
    {
        try {
            $token = $_GET['token'] ?? null;
            if (!$token) throw new \Exception("Token manquant.");

            // Récupération de la réservation via token
            $reservation = $this->reservationModel->findByToken($token);

            if (!$this->reservationModel->reservationModifiable($reservation)) {
                throw new \Exception("Impossible d'annuler cette réservation : le vol n'est plus modifiable.");
            }

            $id = $reservation["IdReservation"];

            // Annulation de la réservation
            $this->reservationModel->cancelReservation($id);

            Flash::add('success', "Réservation annulée avec succès");

        } catch (\Exception $e) {
            Flash::add('danger', $e->getMessage());
        }
        
        // Redirection
        $this->redirect("/reservation/show?token=$token");
    }

}