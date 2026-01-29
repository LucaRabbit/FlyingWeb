<h1 class="mb-4">Liste des vols</h1>

<?php if (empty($vols)): ?>

    <div class="alert alert-warning">
        Aucun vol trouvé.
    </div>

<?php else: ?>

<table class="table table-striped table-hover shadow-sm">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Numéro</th>
            <th>Départ (UTC)</th>
            <th>Arrivée (UTC)</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($vols as $v): ?>
        <tr>
            <td><?= $v["IdVol"] ?></td>
            <td><?= $v["NumeroVol"] ?></td>
            <td><?= $v["DateHeureDepartUTC"] ?></td>
            <td><?= $v["DateHeureArriveeUTC"] ?></td>
            <td>
                <?php if ($v["StatutVol"] === "Planifie"): ?>
                    <span class="badge bg-primary">Planifié</span>
                <?php elseif ($v["StatutVol"] === "EnCours"): ?>
                    <span class="badge bg-info text-dark">En cours</span>
                <?php elseif ($v["StatutVol"] === "Arrive"): ?>
                    <span class="badge bg-success">Arrivé</span>
                <?php else: ?>
                    <span class="badge bg-secondary"><?= $v["StatutVol"] ?></span>
                <?php endif; ?>
            </td>

            <td>
                <a href="/admin/vol/show/<?= $v["IdVol"] ?>" class="btn btn-sm btn-outline-secondary">
                    Voir
                </a>

                <?php if ($v["StatutVol"] === "Planifie"): ?>
                    <a href="/admin/vol/edit/<?= $v["IdVol"] ?>" class="btn btn-sm btn-outline-primary ms-1">
                        Modifier
                    </a>
                <?php else: ?>
                    <button class="btn btn-sm btn-outline-secondary ms-1" disabled>
                        Modifier
                    </button>
                <?php endif; ?>

                <a href="/admin/vol/passagers/<?= $v["IdVol"] ?>" class="btn btn-sm btn-outline-dark ms-1">
                    Passagers
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>

<p class="mt-3">
    <a href="/admin/vol/create" class="btn btn-success">
        Planifier un vol
    </a>
</p>