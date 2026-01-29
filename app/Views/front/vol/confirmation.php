<h1 class="mb-4">Confirmation de votre sélection</h1>


<!-- VOL ALLER -->
<h2 class="h4 mb-3">Vol aller</h2>

<table class="table table-bordered table-striped shadow-sm mb-5">
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
            <td><?= htmlspecialchars($volAller["AeroportDepartNom"]) ?></td>

            <td><?= date("d/m/Y H:i", strtotime($volAller["DateHeureArriveeUTC"])) ?></td>
            <td><?= htmlspecialchars($volAller["AeroportArriveeNom"]) ?></td>

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


<!-- VOL RETOUR -->
<?php if ($volRetour): ?>

<h2 class="h4 mb-3">Vol retour</h2>

<table class="table table-bordered table-striped shadow-sm mb-5">
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
            <td><?= htmlspecialchars($volRetour["AeroportDepartNom"]) ?></td>

            <td><?= date("d/m/Y H:i", strtotime($volRetour["DateHeureArriveeUTC"])) ?></td>
            <td><?= htmlspecialchars($volRetour["AeroportArriveeNom"]) ?></td>

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

<?php else: ?>

<p class="alert alert-warning">Aucun vol retour sélectionné.</p>

<?php endif; ?>


<!-- BOUTON CONTINUER -->
<p class="mt-4">
    <a href="/reservation/infos?aller=<?= $volAller["IdVol"] ?><?php if ($volRetour): ?>&retour=<?= $volRetour["IdVol"] ?><?php endif; ?>"
       class="btn btn-primary">
        Continuer vers la réservation
    </a>
</p>