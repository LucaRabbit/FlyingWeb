<h1 class="mb-4">Ajouter un avion</h1>

<form method="POST" action="/admin/avion/create" 
      class="card p-4 shadow-sm"
      autocomplete="off"
      style="max-width: 650px;">

    <div class="mb-3">
        <label class="form-label">Immatriculation</label>
        <input type="text" name="Immatriculation" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Modèle</label>
        <input type="text" name="Modele" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Nombre de places passagers</label>
        <input type="number" name="NbPlacesPassager" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Longueur de l'avion (m)</label>
        <input type="number" step="0.01" name="LongueurAvion" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Statut</label>
        <select name="StatutAvion" class="form-select" required>
            <option value="AuSol">Au sol</option>
            <option value="EnVol">En vol</option>
            <option value="Maintenance">Maintenance</option>
            <option value="HorsService">Hors service</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Stationné à l'aéroport</label>
        <select name="IdAeroportActuel" class="form-select">
            <?php foreach ($aeroports as $ap): ?>
                <option value="<?= $ap["IdAeroport"] ?>">
                    <?= $ap["NomAeroport"] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-success w-100">
        Créer
    </button>

</form>

<a href="/admin/avion" class="btn btn-secondary mt-3">Retour</a>