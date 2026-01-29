<h1 class="mb-4 text-center">Carte d'embarquement</h1>

<div class="card shadow-sm p-4 mb-4" style="max-width: 700px; margin: auto;">

    <h5 class="mb-3 text-primary fw-bold">Informations de réservation</h5>
    <p><strong>Réservation :</strong> <?= $reservation["IdReservation"] ?></p>
    <p><strong>Date :</strong> <?= $reservation["DateReservation"] ?></p>

    <hr>

    <h5 class="mb-3 text-primary fw-bold">Vol aller</h5>
    <p class="mb-4">
        <strong>Numéro :</strong> <?= $volAller["NumeroVol"] ?><br>
        <strong>Départ :</strong> <?= date("d/m/Y H:i", strtotime($volAller["DateHeureDepartUTC"])) ?><br>
        <strong>Aéroport départ :</strong> <?= $aeroDepartAller["NomAeroport"] ?> (<?= $aeroDepartAller["Pays"] ?>)<br>
        <strong>Arrivée :</strong> <?= date("d/m/Y H:i", strtotime($volAller["DateHeureArriveeUTC"])) ?><br>
        <strong>Aéroport arrivée :</strong> <?= $aeroArriveeAller["NomAeroport"] ?> (<?= $aeroArriveeAller["Pays"] ?>)
    </p>

    <?php if ($volRetour): ?>
        <hr>
        <h5 class="mb-3 text-primary fw-bold">Vol retour</h5>
        <p class="mb-4">
            <strong>Numéro :</strong> <?= $volRetour["NumeroVol"] ?><br>
            <strong>Départ :</strong> <?= date("d/m/Y H:i", strtotime($volRetour["DateHeureDepartUTC"])) ?><br>
            <strong>Aéroport départ :</strong> <?= $aeroDepartRetour["NomAeroport"] ?> (<?= $aeroDepartRetour["Pays"] ?>)<br>
            <strong>Arrivée :</strong> <?= date("d/m/Y H:i", strtotime($volRetour["DateHeureArriveeUTC"])) ?><br>
            <strong>Aéroport arrivée :</strong> <?= $aeroArriveeRetour["NomAeroport"] ?> (<?= $aeroArriveeRetour["Pays"] ?>)
        </p>
    <?php endif; ?>

    <hr>

    <div class="text-center">
        <h5 class="fw-bold mb-3">Votre QR Code</h5>
        <p class="text-muted">Scannez-le pour accéder à votre réservation.</p>

        <!-- QR code ici -->
        <div class="my-3">
            <?= $qrCodeHtml ?? '' ?>
        </div>

        <p class="mt-4 text-success fw-semibold">Merci d'avoir choisi FlyingWeb</p>
    </div>

</div>