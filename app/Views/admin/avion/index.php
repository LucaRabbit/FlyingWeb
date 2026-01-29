<h1 class="mb-4">Liste des avions</h1>

<?php if (empty($avions)): ?>

    <div class="alert alert-warning">
        Aucun avion trouvé.
    </div>

<?php else: ?>

<table class="table table-striped table-hover shadow-sm">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Immatriculation</th>
            <th>Modèle</th>
            <th>Places</th>
            <th>Longueur</th>
            <th>Statut</th>
            <th>Aéroport actuel</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($avions as $a): ?>
        <tr>
            <td><?= $a["IdAvion"] ?></td>
            <td><?= $a["Immatriculation"] ?></td>
            <td><?= $a["Modele"] ?></td>
            <td><?= $a["NbPlacesPassager"] ?></td>
            <td><?= $a["LongueurAvion"] ?></td>

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

            <td><?= $a["NomAeroportActuel"] ?></td>

            <td>
                <div class="d-flex gap-2 align-items-center">

                    <a href="/admin/avion/show/<?= $a["IdAvion"] ?>" 
                    class="btn btn-sm btn-outline-secondary">
                        Voir
                    </a>

                    <?php if ($a["modifiable"]): ?>
                        <a href="/admin/avion/edit/<?= $a["IdAvion"] ?>" class="btn btn-sm btn-outline-primary ms-1">
                            Modifier
                        </a>
                    <?php else: ?>
                            <button class="btn btn-sm btn-outline-secondary ms-1" disabled>
                                Modifier
                            </button>
                    <?php endif; ?>

                    <?php if ($a["modifiable"]): ?>
                        <form action="/admin/avion/delete/<?= $a["IdAvion"] ?>" 
                            method="POST" 
                            class="d-inline"
                            onsubmit="return confirm('Supprimer cet avion ?')">
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                Supprimer
                            </button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-sm btn-outline-secondary" disabled>
                            Supprimer
                        </button>
                    <?php endif; ?>

                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>

<p class="mt-3">
    <a href="/admin/avion/create" class="btn btn-success">
        Ajouter un avion
    </a>
</p>