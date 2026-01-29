<h1 class="mb-4">Détails de l'aéroport #<?= $aeroport["IdAeroport"] ?></h1>

<div class="card p-4 shadow-sm mb-4">

    <p><strong>Code IATA :</strong> <?= $aeroport["CodeIATA"] ?></p>
    <p><strong>Nom officiel :</strong> <?= $aeroport["NomOfficiel"] ?></p>
    <p><strong>Ville :</strong> <?= $aeroport["Ville"] ?></p>
    <p><strong>Pays :</strong> <?= $aeroport["Pays"] ?></p>
    <p><strong>Longueur avion max :</strong> <?= $aeroport["LongueurAvionMax"] ?></p>

</div>


<h2 class="h4 mb-3">Vols associés</h2>

<?php if (empty($vols)): ?>

    <div class="alert alert-info">
        Aucun vol associé à cet aéroport.
    </div>

<?php else: ?>

<table class="table table-striped table-hover shadow-sm mb-4">
    <thead class="table-dark">
        <tr>
            <th>Numéro</th>
            <th>Départ</th>
            <th>Arrivée</th>
            <th>Statut</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($vols as $v): ?>
        <tr>
            <td><?= $v["NumeroVol"] ?></td>
            <td><?= $v["DateHeureDepartUTC"] ?></td>
            <td><?= $v["DateHeureArriveeUTC"] ?></td>
            <td>
                <?php if ($v["StatutVol"] === "Planifie"): ?>
                    <span class="badge bg-primary">Planifié</span>
                <?php elseif ($v["StatutVol"] === "EnCours"): ?>
                    <span class="badge bg-info text-dark">En cours</span>
                <?php elseif ($v["StatutVol"] === "Termine"): ?>
                    <span class="badge bg-success">Terminé</span>
                <?php else: ?>
                    <span class="badge bg-secondary"><?= $v["StatutVol"] ?></span>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>


<h2 class="h4 mb-3">Avions stationnés</h2>

<?php if (empty($avions)): ?>

    <div class="alert alert-info">
        Aucun avion n'est actuellement stationné dans cet aéroport.
    </div>

<?php else: ?>

<table class="table table-striped table-hover shadow-sm mb-4">
    <thead class="table-dark">
        <tr>
            <th>Immatriculation</th>
            <th>Modèle</th>
            <th>Places</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($avions as $a): ?>
        <tr>
            <td><?= $a["Immatriculation"] ?></td>
            <td><?= $a["Modele"] ?></td>
            <td><?= $a["NbPlacesPassager"] ?></td>
            <td>
                <?php if ($a["StatutAvion"] === "Disponible"): ?>
                    <span class="badge bg-success">Disponible</span>
                <?php elseif ($a["StatutAvion"] === "Maintenance"): ?>
                    <span class="badge bg-warning text-dark">Maintenance</span>
                <?php elseif ($a["StatutAvion"] === "EnVol"): ?>
                    <span class="badge bg-info text-dark">En vol</span>
                <?php else: ?>
                    <span class="badge bg-secondary"><?= $a["StatutAvion"] ?></span>
                <?php endif; ?>
            </td>
            <td>
                <a href="/admin/avion/show/<?= $a["IdAvion"] ?>" 
                   class="btn btn-sm btn-outline-secondary">
                    Voir
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>


<div class="d-flex gap-3 mt-3">

    <a href="/admin/aeroport" class="btn btn-secondary">Retour</a>

    <?php if (!$aeroport["modifiable"]): ?>
        <button class="btn btn-sm btn-outline-secondary" disabled>
            Modifier
        </button>
    <?php else: ?>
        <a href="/admin/aeroport/edit/<?= $aeroport["IdAeroport"] ?>" 
           class="btn btn-primary">
            Modifier
        </a>
    <?php endif; ?>

</div>