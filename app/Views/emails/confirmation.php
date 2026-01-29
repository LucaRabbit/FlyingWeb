<h2 class="mb-4 text-center text-success fw-bold">Votre réservation est confirmée</h2>

<div class="card shadow-sm p-4 mb-4" style="max-width: 750px; margin: auto;">

    <!-- Infos réservation -->
    <h5 class="text-primary fw-bold mb-3">Informations de réservation</h5>
    <p><strong>Numéro :</strong> <?= $reservation["IdReservation"] ?></p>
    <p><strong>Date :</strong> <?= $reservation["DateReservation"] ?></p>

    <p><strong>Code d’accès :</strong> <?= $reservation["TokenLien"] ?></p>

    <p>
    <a href="http://flyingweb.local/reservation/show?token=<?= $reservation["TokenLien"] ?>">
        Voir la réservation en ligne
    </a>

    <hr>

    <!-- Vol aller -->
    <h5 class="text-primary fw-bold mb-3">Vol aller</h5>
    <p class="mb-3">
        <strong>Numéro :</strong> <?= $volAller["NumeroVol"] ?><br>
        <strong>Départ :</strong> <?= date("d/m/Y H:i", strtotime($volAller["DateHeureDepartUTC"])) ?><br>
        <strong>Aéroport départ :</strong> <?= $aeroDepartAller["NomAeroport"] ?> (<?= $aeroDepartAller["Pays"] ?>)<br>
        <strong>Arrivée :</strong> <?= date("d/m/Y H:i", strtotime($volAller["DateHeureArriveeUTC"])) ?><br>
        <strong>Aéroport arrivée :</strong> <?= $aeroArriveeAller["NomAeroport"] ?> (<?= $aeroArriveeAller["Pays"] ?>)
    </p>

    <h6 class="fw-bold">Passagers (aller)</h6>
    <ul class="mb-4">
        <?php foreach ($passagers as $p): ?>
            <?php if ($p["IdVol"] == $volAller["IdVol"]): ?>
                <li><?= $p["Nom"] ?> <?= $p["Prenom"] ?> — Siège <?= $p["NumeroSiege"] ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

    <!-- Vol retour -->
    <?php if ($volRetour): ?>
        <hr>

        <h5 class="text-primary fw-bold mb-3">Vol retour</h5>
        <p class="mb-3">
            <strong>Numéro :</strong> <?= $volRetour["NumeroVol"] ?><br>
            <strong>Départ :</strong> <?= date("d/m/Y H:i", strtotime($volRetour["DateHeureDepartUTC"])) ?><br>
            <strong>Aéroport départ :</strong> <?= $aeroDepartRetour["NomAeroport"] ?> (<?= $aeroDepartRetour["Pays"] ?>)<br>
            <strong>Arrivée :</strong> <?= date("d/m/Y H:i", strtotime($volRetour["DateHeureArriveeUTC"])) ?><br>
            <strong>Aéroport arrivée :</strong> <?= $aeroArriveeRetour["NomAeroport"] ?> (<?= $aeroArriveeRetour["Pays"] ?>)
        </p>

        <h6 class="fw-bold">Passagers (retour)</h6>
        <ul class="mb-4">
            <?php foreach ($passagers as $p): ?>
                <?php if ($p["IdVol"] == $volRetour["IdVol"]): ?>
                    <li><?= $p["Nom"] ?> <?= $p["Prenom"] ?> — Siège <?= $p["NumeroSiege"] ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <div class="text-center mt-4">
        <p class="text-success fw-semibold">Merci d'avoir choisi FlyingWeb</p>
    </div>

</div>