<h1 class="mb-4">Passagers du vol <?= $vol["NumeroVol"] ?></h1>

<?php if (empty($passagers)): ?>

    <div class="alert alert-warning">
        Aucun passager pour ce vol.
    </div>

<?php else: ?>

<table class="table table-striped table-hover shadow-sm">
    <thead class="table-dark">
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Siège</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($passagers as $p): ?>
        <tr>
            <td><?= $p["Nom"] ?></td>
            <td><?= $p["Prenom"] ?></td>
            <td><?= $p["NumeroSiege"] ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>

<a href="/admin/vol" class="btn btn-secondary mt-3">
    Retour aux vols
</a>