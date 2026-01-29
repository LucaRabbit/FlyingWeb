<h1 class="mb-4">Planifier un vol</h1>

<form method="POST" action="/admin/vol/create" class="card p-4 shadow-sm" style="max-width: 650px;">

    <div class="mb-3">
        <label class="form-label">Numéro du vol</label>
        <input type="text" name="NumeroVol" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Date et heure de départ (UTC)</label>
        <input type="datetime-local" name="DateHeureDepartUTC" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Date et heure d'arrivée (UTC)</label>
        <input type="datetime-local" name="DateHeureArriveeUTC" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Statut du vol</label>
        <select name="StatutVol" class="form-select" required>
            <option value="Planifie">Planifié</option>
            <option value="EnCours">En cours</option>
            <option value="Arrive">Arrivé</option>
            <option value="Annule">Annulé</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Avion</label>
        <select name="IdAvion" class="form-select" required>
            <?php foreach ($avions as $a): ?>
                <option value="<?= $a["IdAvion"] ?>">
                    <?= $a["Immatriculation"] ?> (<?= $a["Modele"] ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Aéroport de départ</label>
        <select name="IdAeroportDepart" class="form-select" required>
            <?php foreach ($aeroports as $ap): ?>
                <option value="<?= $ap["IdAeroport"] ?>">
                    <?= $ap["NomAeroport"] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Aéroport d'arrivée</label>
        <select name="IdAeroportArrivee" class="form-select" required>
            <?php foreach ($aeroports as $ap): ?>
                <option value="<?= $ap["IdAeroport"] ?>">
                    <?= $ap["NomAeroport"] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-success w-100">Créer</button>

</form>

<a href="/admin/vol" class="btn btn-secondary mt-3">Retour</a>

<script>
document.querySelector('form').addEventListener('submit', function (e) {
    const depart = new Date(document.querySelector('[name="DateHeureDepartUTC"]').value);
    const arrivee = new Date(document.querySelector('[name="DateHeureArriveeUTC"]').value);
    const now = new Date();

    if (depart < now) {
        e.preventDefault();
        alert("La date de départ ne peut pas être dans le passé.");
        return;
    }

    if (arrivee < now) {
        e.preventDefault();
        alert("La date d'arrivée ne peut pas être dans le passé.");
        return;
    }

    if (arrivee <= depart) {
        e.preventDefault();
        alert("La date d'arrivée doit être strictement supérieure à la date de départ.");
        return;
    }
});
</script>