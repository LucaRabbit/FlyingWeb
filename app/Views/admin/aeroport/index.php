<h1 class="mb-4">Liste des aéroports</h1>

<?php if (empty($aeroports)): ?>

    <div class="alert alert-warning">
        Aucun aéroport trouvé.
    </div>

<?php else: ?>

<table class="table table-striped table-hover shadow-sm">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Code IATA</th>
            <th>Nom officiel</th>
            <th>Ville</th>
            <th>Pays</th>
            <th>Longueur avion max</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($aeroports as $a): ?>
        <tr>
            <td><?= $a["IdAeroport"] ?></td>
            <td><?= $a["CodeIATA"] ?></td>
            <td><?= $a["NomOfficiel"] ?></td>
            <td><?= $a["Ville"] ?></td>
            <td><?= $a["Pays"] ?></td>
            <td><?= $a["LongueurAvionMax"] ?></td>

        <td class="d-flex gap-2">

            <a href="/admin/aeroport/show/<?= $a["IdAeroport"] ?>" 
            class="btn btn-sm btn-outline-secondary">
                Voir
            </a>

            <?php if (!$a["modifiable"]): ?>

                <button class="btn btn-sm btn-outline-secondary" disabled>
                    Modifier
                </button>
                <button class="btn btn-sm btn-outline-secondary" disabled>
                    Supprimer
                </button>

            <?php else: ?>

                <a href="/admin/aeroport/edit/<?= $a["IdAeroport"] ?>" 
                class="btn btn-sm btn-outline-primary">
                    Modifier
                </a>

                <form action="/admin/aeroport/delete/<?= $a["IdAeroport"] ?>" 
                    method="POST" 
                    onsubmit="return confirm('Supprimer cet aéroport ?')">
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        Supprimer
                    </button>
                </form>

            <?php endif; ?>

        </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>

<p class="mt-3">
    <a href="/admin/aeroport/create" class="btn btn-success">
        Ajouter un aéroport
    </a>
</p>