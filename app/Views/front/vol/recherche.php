<!-- Formulaire de recherche -->
<h1 class="mb-4">Rechercher un vol</h1>

<form action="/vol/recherche" method="POST" autocomplete="off" class="card p-3 shadow-sm mb-4">

    <div class="row g-3">

        <div class="col-md-6">
            <label class="form-label small">Ville de départ</label>
            <input list="listeVilles" name="villeDepart" class="form-control form-control-sm"
                value="<?= htmlspecialchars($villeDepart) ?>" required>

            <datalist id="listeVilles">
                <?php foreach ($villes as $v): ?>
                    <option value="<?= htmlspecialchars($v) ?>"></option>
                <?php endforeach; ?>
            </datalist>
        </div>

        <div class="col-md-6">
            <label class="form-label small">Ville d'arrivée</label>
            <input list="listeVilles" name="villeArrivee" class="form-control form-control-sm"
                value="<?= htmlspecialchars($villeArrivee) ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label small">Date de départ</label>
            <input type="date" name="dateDepart" class="form-control form-control-sm"
                   value="<?= htmlspecialchars($dateDepart) ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label small">Tolérance (en jours)</label>
            <input type="number" name="tolerance" class="form-control form-control-sm"
                   value="<?= htmlspecialchars($tolerance) ?>" min="0">
        </div>

    </div>

    <button type="submit" class="btn btn-primary btn-sm w-100 mt-3">
        Rechercher
    </button>
</form>


<!-- Résultats -->
<?php if (empty($volsAller)) : ?>

    <div class="alert alert-warning text-center">
        <strong>Aucun vol trouvé.</strong>
    </div>

<?php else: ?>

    <?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
        <h2 class="mb-4">
            Vols aller : <?= htmlspecialchars($villeDepart) ?> → <?= htmlspecialchars($villeArrivee) ?>
        </h2>
    <?php else: ?>
        <h2 class="mb-4">Tous les vols disponibles</h2>
    <?php endif; ?>

    <table class="table table-striped table-hover align-middle shadow-sm">
        <thead class="table-primary">
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
        <?php foreach ($volsAller as $vol): ?>
            <tr>
                <td><?= $vol["NumeroVol"] ?></td>

                <td><?= date("d/m/Y H:i", strtotime($vol["DateHeureDepartUTC"])) ?></td>

                <td><?= htmlspecialchars($vol["AeroportDepartNom"]) ?></td>

                <td><?= date("d/m/Y H:i", strtotime($vol["DateHeureArriveeUTC"])) ?></td>

                <td><?= htmlspecialchars($vol["AeroportArriveeNom"]) ?></td>

                <td>
                    <?php
                        $d = strtotime($vol["DateHeureDepartUTC"]);
                        $a = strtotime($vol["DateHeureArriveeUTC"]);
                        echo gmdate("H\hi", $a - $d);
                    ?>
                </td>

                <td>
                    <?php if ($vol["Complet"]): ?>
                        <span class="badge bg-danger">Complet</span>
                    <?php else: ?>
                        <span class="badge bg-success"><?= $vol["PlacesRestantes"] ?> places</span>
                    <?php endif; ?>
                </td>

                <td>
                    <?php if (!$vol["Complet"]): ?>
                        <a href="/vol/retour/<?= $vol["IdVol"] ?>" class="btn btn-sm btn-primary">
                            Choisir
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php endif; ?>