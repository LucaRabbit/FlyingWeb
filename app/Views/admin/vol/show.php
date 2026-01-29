<h1 class="mb-4">Détails du vol #<?= $vol["IdVol"] ?></h1>

<div class="card p-4 shadow-sm mb-4">

    <p><strong>Numéro :</strong> <?= $vol["NumeroVol"] ?></p>
    <p><strong>Date départ (UTC) :</strong> <?= $vol["DateHeureDepartUTC"] ?></p>
    <p><strong>Date arrivée (UTC) :</strong> <?= $vol["DateHeureArriveeUTC"] ?></p>

    <p>
        <strong>Statut :</strong>
        <?php if ($vol["StatutVol"] === "Planifie"): ?>
            <span class="badge bg-primary">Planifié</span>
        <?php elseif ($vol["StatutVol"] === "EnCours"): ?>
            <span class="badge bg-info text-dark">En cours</span>
        <?php elseif ($vol["StatutVol"] === "Arrive"): ?>
            <span class="badge bg-success">Arrivé</span>
        <?php else: ?>
            <span class="badge bg-secondary"><?= $vol["StatutVol"] ?></span>
        <?php endif; ?>
    </p>

    <p><strong>Avion :</strong>
        <?= $avion["Immatriculation"] ?> (<?= $avion["Modele"] ?>)
    </p>

    <p><strong>Aéroport de départ :</strong>
        <?= $aeroportDepart["NomAeroport"] ?>
    </p>

    <p><strong>Aéroport d'arrivée :</strong>
        <?= $aeroportArrivee["NomAeroport"] ?>
    </p>

</div>


<!-- ACTIONS SELON STATUT -->
<div class="d-flex gap-2 mb-4">

    <?php if ($vol["StatutVol"] === "Planifie"): ?>
        <a href="/admin/vol/decoller/<?= $vol['IdVol'] ?>" class="btn btn-warning">
            Faire décoller
        </a>
    <?php endif; ?>

    <?php if ($vol["StatutVol"] === "EnCours"): ?>
        <a href="/admin/vol/atterrir/<?= $vol['IdVol'] ?>" class="btn btn-success">
            Faire atterrir
        </a>
    <?php endif; ?>

</div>


<!-- LIENS SECONDAIRES -->
<div class="d-flex gap-2 mt-3">

    <a href="/admin/vol" class="btn btn-secondary">
        Retour
    </a>

    <a href="/admin/vol/passagers/<?= $vol["IdVol"] ?>" class="btn btn-outline-dark">
        Voir les passagers
    </a>

    <?php if ($vol["StatutVol"] === "Planifie"): ?>
        <a href="/admin/vol/edit/<?= $vol["IdVol"] ?>" class="btn btn-outline-primary">
            Modifier
        </a>
    <?php endif; ?>

</div>