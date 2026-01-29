<h1 class="mb-4">Récapitulatif de votre réservation</h1>

<!-- Iinformations Réservant -->
<h2 class="h4 mb-3">Informations du réservant</h2>

<p><strong>Email :</strong> <?= htmlspecialchars($reservation["EmailReservant"]) ?></p>
<p><strong>Nombre de passagers :</strong> <?= $reservation["NbPassagersReserve"] ?></p>


<!-- Vol Aller -->
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
            <td><?= htmlspecialchars($volAller["AeroportDepartNom"]) ?> (<?= htmlspecialchars($volAller["VilleDepart"]) ?>)</td>
            <td><?= date("d/m/Y H:i", strtotime($volAller["DateHeureArriveeUTC"])) ?></td>
            <td><?= htmlspecialchars($volAller["AeroportArriveeNom"]) ?> (<?= htmlspecialchars($volAller["VilleArrivee"]) ?>)</td>

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


<!-- Vol Retour -->
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
            <td><?= htmlspecialchars($volRetour["AeroportDepartNom"]) ?> (<?= htmlspecialchars($volRetour["VilleDepart"]) ?>)</td>
            <td><?= date("d/m/Y H:i", strtotime($volRetour["DateHeureArriveeUTC"])) ?></td>
            <td><?= htmlspecialchars($volRetour["AeroportArriveeNom"]) ?> (<?= htmlspecialchars($volRetour["VilleArrivee"]) ?>)</td>

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

<?php endif; ?>


<!-- Passagers -->
<h2 class="h4 mt-4 mb-3">Passagers</h2>

<ul class="list-group mb-4">
<?php foreach ($passagers as $p): ?>
    <li class="list-group-item">
        <strong><?= htmlspecialchars($p["Nom"]) ?> <?= htmlspecialchars($p["Prenom"]) ?></strong>
    </li>
<?php endforeach; ?>
</ul>


<!-- Bouton confirmation -->
<form action="/reservation/confirm" method="POST">
    <button type="submit" class="btn btn-success w-100 py-2">
        Confirmer la réservation
    </button>
</form>