<h1 class="mb-4">Réservation confirmée</h1>

<p class="alert alert-success">
    Merci pour votre réservation ! Voici le récapitulatif complet.
</p>


<!-- INFORMATIONS DE LA RÉSERVATION -->
<h2 class="h4 mb-3">Informations de la réservation</h2>

<p><strong>Numéro de réservation :</strong> <?= $reservation["IdReservation"] ?></p>

<p><strong>Date de réservation :</strong>
    <?= date("d/m/Y H:i", strtotime($reservation["DateReservation"])) ?>
</p>

<p><strong>Email du réservant :</strong> <?= htmlspecialchars($reservation["EmailReservant"]) ?></p>

<p><strong>Nombre de passagers :</strong> <?= $reservation["NbPassagersReserve"] ?></p>

<?php if (!empty($reservation["TokenLien"])): ?>
<p><strong>Code d’accès :</strong> <?= $reservation["TokenLien"] ?></p>

<p>
    <a href="/reservation/show?token=<?= $reservation["TokenLien"] ?>" class="btn btn-outline-primary btn-sm">
        Voir la réservation en ligne
    </a>
</p>
<?php endif; ?>


<!-- VOL ALLER -->
<h2 class="h4 mt-4 mb-3">Vol aller</h2>

<table class="table table-bordered table-striped shadow-sm mb-4">
    <thead class="table-primary">
        <tr>
            <th>Numéro</th>
            <th>Départ</th>
            <th>Aéroport départ</th>
            <th>Arrivée</th>
            <th>Aéroport arrivée</th>
            <th>Durée</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td><?= $volAller["NumeroVol"] ?></td>
            <td><?= date("d/m/Y H:i", strtotime($volAller["DateHeureDepartUTC"])) ?></td>
            <td><?= $aeroDepartAller["NomAeroport"] ?> (<?= $aeroDepartAller["Ville"] ?>)</td>
            <td><?= date("d/m/Y H:i", strtotime($volAller["DateHeureArriveeUTC"])) ?></td>
            <td><?= $aeroArriveeAller["NomAeroport"] ?> (<?= $aeroArriveeAller["Ville"] ?>)</td>

            <!-- Durée -->
            <td>
                <?php
                    $d = strtotime($volAller["DateHeureDepartUTC"]);
                    $a = strtotime($volAller["DateHeureArriveeUTC"]);
                    echo gmdate("H\hi", $a - $d);
                ?>
            </td>
        </tr>
    </tbody>
</table>


<!-- PASSAGERS ALLER -->
<h2 class="h5 mt-4 mb-3">Passagers (vol aller)</h2>

<ul class="list-group mb-4">
<?php foreach ($passagers as $p): ?>
    <?php if ($p["IdVol"] == $volAller["IdVol"]): ?>
        <li class="list-group-item">
            <?= $p["Nom"] ?> <?= $p["Prenom"] ?> — Siège <?= $p["NumeroSiege"] ?>
        </li>
    <?php endif; ?>
<?php endforeach; ?>
</ul>


<!-- VOL RETOUR -->
<?php if ($volRetour): ?>

<h2 class="h4 mt-4 mb-3">Vol retour</h2>

<table class="table table-bordered table-striped shadow-sm mb-4">
    <thead class="table-secondary">
        <tr>
            <th>Numéro</th>
            <th>Départ</th>
            <th>Aéroport départ</th>
            <th>Arrivée</th>
            <th>Aéroport arrivée</th>
            <th>Durée</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td><?= $volRetour["NumeroVol"] ?></td>
            <td><?= date("d/m/Y H:i", strtotime($volRetour["DateHeureDepartUTC"])) ?></td>
            <td><?= $aeroDepartRetour["NomAeroport"] ?> (<?= $aeroDepartRetour["Ville"] ?>)</td>
            <td><?= date("d/m/Y H:i", strtotime($volRetour["DateHeureArriveeUTC"])) ?></td>
            <td><?= $aeroArriveeRetour["NomAeroport"] ?> (<?= $aeroArriveeRetour["Ville"] ?>)</td>

            <!-- Durée -->
            <td>
                <?php
                    $d = strtotime($volRetour["DateHeureDepartUTC"]);
                    $a = strtotime($volRetour["DateHeureArriveeUTC"]);
                    echo gmdate("H\hi", $a - $d);
                ?>
            </td>
        </tr>
    </tbody>
</table>


<!-- PASSAGERS RETOUR -->
<h2 class="h5 mt-4 mb-3">Passagers (vol retour)</h2>

<ul class="list-group mb-4">
<?php foreach ($passagers as $p): ?>
    <?php if ($p["IdVol"] == $volRetour["IdVol"]): ?>
        <li class="list-group-item">
            <?= $p["Nom"] ?> <?= $p["Prenom"] ?> — Siège <?= $p["NumeroSiege"] ?>
        </li>
    <?php endif; ?>
<?php endforeach; ?>
</ul>

<?php endif; ?>


<!-- MESSAGE FINAL -->
<p class="alert alert-info">
    Un email de confirmation vous a été envoyé.
</p>

<p>
    <a href="/front" class="btn btn-secondary">Retour à l’accueil</a>
</p>