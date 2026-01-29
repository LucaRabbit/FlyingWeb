<h1 class="mb-4">Détails de l'avion #<?= $avion["IdAvion"] ?></h1>

<div class="card p-4 shadow-sm mb-4">

    <p><strong>Immatriculation :</strong> <?= $avion["Immatriculation"] ?></p>
    <p><strong>Modèle :</strong> <?= $avion["Modele"] ?></p>
    <p><strong>Nombre de places :</strong> <?= $avion["NbPlacesPassager"] ?></p>
    <p><strong>Longueur :</strong> <?= $avion["LongueurAvion"] ?> m</p>

    <p>
        <strong>Statut :</strong>
        <?php if ($avion["StatutAvion"] === "Disponible"): ?>
            <span class="badge bg-success">Disponible</span>
        <?php elseif ($avion["StatutAvion"] === "Maintenance"): ?>
            <span class="badge bg-warning text-dark">Maintenance</span>
        <?php elseif ($avion["StatutAvion"] === "EnVol"): ?>
            <span class="badge bg-info text-dark">En vol</span>
        <?php else: ?>
            <span class="badge bg-secondary"><?= $avion["StatutAvion"] ?></span>
        <?php endif; ?>
    </p>

</div>


<h2 class="h4 mb-3">Vols associés</h2>

<?php if (empty($vols)): ?>

    <div class="alert alert-info">
        Aucun vol pour cet avion.
    </div>

<?php else: ?>

<table class="table table-striped table-hover shadow-sm">
    <thead class="table-dark">
        <tr>
            <th>Numéro</th>
            <th>Départ</th>
            <th>Arrivée</th>
            <th>Statut</th>
            <th>Actions</th>
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

            <td>
                <a href="/admin/vol/show/<?= $v["IdVol"] ?>" 
                   class="btn btn-sm btn-outline-secondary">
                    Voir
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>


<div class="d-flex gap-2 mt-3">
    <a href="/admin/avion" class="btn btn-secondary">Retour</a>

    <?php if ($avion["modifiable"]): ?>
        <a href="/admin/avion/edit/<?= $avion["IdAvion"] ?>" 
           class="btn btn-primary">
            Modifier
        </a>
    <?php else: ?>
        <button class="btn btn-secondary" disabled>
            Modifier
        </button>
    <?php endif; ?>
</div>