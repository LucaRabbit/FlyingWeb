<h1 class="mb-4">Choix du vol retour</h1>

<h2 class="h4 mb-3">Vol aller sélectionné</h2>

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

            <!-- Départ -->
            <td><?= date("d/m/Y H:i", strtotime($volAller["DateHeureDepartUTC"])) ?></td>
            <td><?= htmlspecialchars($volAller["AeroportDepartNom"]) ?></td>

            <!-- Arrivée -->
            <td><?= date("d/m/Y H:i", strtotime($volAller["DateHeureArriveeUTC"])) ?></td>
            <td><?= htmlspecialchars($volAller["AeroportArriveeNom"]) ?></td>

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


<h2 class="h4 mb-3">Vols retour proposés</h2>

<?php if (empty($volsRetour)): ?>

    <div class="alert alert-warning">
        Aucun vol retour disponible.
    </div>

<?php else: ?>

<table class="table table-hover table-striped shadow-sm">
    <thead class="table-secondary">
        <tr>
            <th>Numéro</th>
            <th>Départ</th>
            <th>Aéroport départ</th>
            <th>Arrivée</th>
            <th>Aéroport arrivée</th>
            <th>Durée</th>
            <th>Places restantes</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($volsRetour as $vol): ?>
        <tr>
            <td><?= $vol["NumeroVol"] ?></td>

            <!-- Départ -->
            <td><?= date("d/m/Y H:i", strtotime($vol["DateHeureDepartUTC"])) ?></td>
            <td><?= htmlspecialchars($vol["AeroportDepartNom"]) ?></td>

            <!-- Arrivée -->
            <td><?= date("d/m/Y H:i", strtotime($vol["DateHeureArriveeUTC"])) ?></td>
            <td><?= htmlspecialchars($vol["AeroportArriveeNom"]) ?></td>

            <!-- Durée -->
            <td>
                <?php
                    $d = strtotime($vol["DateHeureDepartUTC"]);
                    $a = strtotime($vol["DateHeureArriveeUTC"]);
                    echo gmdate("H\hi", $a - $d);
                ?>
            </td>

            <!-- Places restantes -->
            <td>
                <?php if ($vol["Complet"]): ?>
                    <span class="badge bg-danger">Complet</span>
                <?php else: ?>
                    <span class="badge bg-success"><?= $vol["PlacesRestantes"] ?> places</span>
                <?php endif; ?>
            </td>

            <!-- Bouton choisir -->
            <td>
                <?php if ($vol["Complet"]): ?>
                    <span></span>
                <?php else: ?>
                    <a href="/vol/terminer/aller-retour/<?= $volAller["IdVol"] ?>/<?= $vol["IdVol"] ?>"
                       class="btn btn-sm btn-primary">
                        Choisir
                    </a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>


<p class="mt-4">
    <a href="/vol/terminer/aller/<?= $volAller["IdVol"] ?>" class="btn btn-outline-secondary">
        Continuer sans vol retour
    </a>
</p>