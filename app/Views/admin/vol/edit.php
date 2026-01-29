<h1 class="mb-4">Modifier le vol #<?= $vol["IdVol"] ?></h1>

<?php if ($vol["StatutVol"] !== "Planifie"): ?>

    <div class="alert alert-danger">
        Ce vol ne peut plus être modifié car il n'est plus en statut <strong>Planifié</strong>.
    </div>

    <a href="/admin/vol" class="btn btn-secondary">Retour</a>

<?php else: ?>

<form method="POST" action="/admin/vol/update/<?= $vol["IdVol"] ?>" class="card p-4 shadow-sm" style="max-width: 600px;">

    <div class="mb-3">
        <label class="form-label">Numéro du vol</label>
        <input type="text" name="NumeroVol" value="<?= $vol["NumeroVol"] ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Date et heure de départ (UTC)</label>
        <input type="datetime-local" name="DateHeureDepartUTC"
               value="<?= date('Y-m-d\TH:i', strtotime($vol["DateHeureDepartUTC"])) ?>"
               class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Date et heure d'arrivée (UTC)</label>
        <input type="datetime-local" name="DateHeureArriveeUTC"
               value="<?= date('Y-m-d\TH:i', strtotime($vol["DateHeureArriveeUTC"])) ?>"
               class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Statut du vol</label>
        <select name="StatutVol" class="form-select" required>
            <option value="Planifie" <?= $vol["StatutVol"] === "Planifie" ? "selected" : "" ?>>Planifié</option>
            <option value="EnCours" <?= $vol["StatutVol"] === "EnCours" ? "selected" : "" ?>>En cours</option>
            <option value="Arrive" <?= $vol["StatutVol"] === "Arrive" ? "selected" : "" ?>>Arrivé</option>
            <option value="Annule" <?= $vol["StatutVol"] === "Annule" ? "selected" : "" ?>>Annulé</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Avion</label>
        <select name="IdAvion" class="form-select" required>
            <?php foreach ($avions as $a): ?>
                <option value="<?= $a["IdAvion"] ?>"
                    <?= $a["IdAvion"] == $vol["IdAvion"] ? "selected" : "" ?>>
                    <?= $a["Immatriculation"] ?> (<?= $a["Modele"] ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Aéroport de départ</label>
        <select name="IdAeroportDepart" class="form-select" required>
            <?php foreach ($aeroports as $ap): ?>
                <option value="<?= $ap["IdAeroport"] ?>"
                    <?= $ap["IdAeroport"] == $vol["IdAeroportDepart"] ? "selected" : "" ?>>
                    <?= $ap["NomAeroport"] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Aéroport d'arrivée</label>
        <select name="IdAeroportArrivee" class="form-select" required>
            <?php foreach ($aeroports as $ap): ?>
                <option value="<?= $ap["IdAeroport"] ?>"
                    <?= $ap["IdAeroport"] == $vol["IdAeroportArrivee"] ? "selected" : "" ?>>
                    <?= $ap["NomAeroport"] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary w-100">Mettre à jour</button>

</form>

<a href="/admin/vol" class="btn btn-secondary mt-3">Retour</a>

<?php endif; ?>