<h1 class="mb-4">Réservation</h1>

<!-- Vol Aller -->
<h2 class="h4 mb-3">Vol aller</h2>

<table class="table table-bordered table-striped shadow-sm mb-4">
    <thead class="table-primary">
        <tr>
            <th>Numéro</th>
            <th>Départ</th>
            <th>Aéroport départ</th>
            <th>Arrivée</th>
            <th>Aéroport arrivée</th>
            <th>Places restantes</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td><?= $volAller["NumeroVol"] ?></td>
            <td><?= date("d/m/Y H:i", strtotime($volAller["DateHeureDepartUTC"])) ?></td>
            <td><?= htmlspecialchars($volAller["AeroportDepartNom"]) ?> (<?= htmlspecialchars($volAller["VilleDepart"]) ?>)</td>
            <td><?= date("d/m/Y H:i", strtotime($volAller["DateHeureArriveeUTC"])) ?></td>
            <td><?= htmlspecialchars($volAller["AeroportArriveeNom"]) ?> (<?= htmlspecialchars($volAller["VilleArrivee"]) ?>)</td>
            <td><?= $placesAller ?></td>
        </tr>
    </tbody>
</table>


<!-- VOL Retour -->
<?php if ($volRetour): ?>

<h2 class="h4 mb-3">Vol retour</h2>

<table class="table table-bordered table-striped shadow-sm mb-4">
    <thead class="table-secondary">
        <tr>
            <th>Numéro</th>
            <th>Départ</th>
            <th>Aéroport départ</th>
            <th>Arrivée</th>
            <th>Aéroport arrivée</th>
            <th>Places restantes</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td><?= $volRetour["NumeroVol"] ?></td>
            <td><?= date("d/m/Y H:i", strtotime($volRetour["DateHeureDepartUTC"])) ?></td>
            <td><?= htmlspecialchars($volRetour["AeroportDepartNom"]) ?> (<?= htmlspecialchars($volRetour["VilleDepart"]) ?>)</td>
            <td><?= date("d/m/Y H:i", strtotime($volRetour["DateHeureArriveeUTC"])) ?></td>
            <td><?= htmlspecialchars($volRetour["AeroportArriveeNom"]) ?> (<?= htmlspecialchars($volRetour["VilleArrivee"]) ?>)</td>
            <td><?= $placesRetour ?></td>
        </tr>
    </tbody>
</table>

<?php endif; ?>


<!-- Formulaire de réservation -->
<h1 class="h4 mt-5 mb-3">Informations de réservation</h1>

<form action="/reservation/save-infos" method="POST" autocomplete="off" class="card p-4 shadow-sm">

    <input type="hidden" name="IdVolAller" value="<?= $idVolAller ?>">
    <input type="hidden" name="IdVolRetour" value="<?= $idVolRetour ?>">

    <div class="mb-3">
        <label class="form-label">Email :</label>
        <input type="email" name="EmailReservant" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Nombre de passagers :</label>
        <input type="number" name="NbPassagersReserve" class="form-control"
               min="1" max="<?= $placesAller ?>" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">Continuer</button>
</form>