<h1 class="mb-4">Modifier l’avion #<?= $avion["IdAvion"] ?></h1>

<form action="/admin/avion/update/<?= $avion["IdAvion"] ?>" 
      method="POST" 
      class="card p-4 shadow-sm mb-4" 
      style="max-width: 650px;">

    <div class="mb-3">
        <label class="form-label">Immatriculation</label>
        <input type="text" name="Immatriculation" 
               value="<?= $avion["Immatriculation"] ?>" 
               class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Modèle</label>
        <input type="text" name="Modele" 
               value="<?= $avion["Modele"] ?>" 
               class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Nombre de places</label>
        <input type="number" name="NbPlacesPassager" 
               value="<?= $avion["NbPlacesPassager"] ?>" 
               class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Longueur (m)</label>
        <input type="number" step="0.01" name="LongueurAvion" 
               value="<?= $avion["LongueurAvion"] ?>" 
               class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Statut</label>
        <select name="StatutAvion" class="form-select">
            <option value="AuSol"       <?= $avion["StatutAvion"] === "AuSol" ? "selected" : "" ?>>Au sol</option>
            <option value="EnVol"       <?= $avion["StatutAvion"] === "EnVol" ? "selected" : "" ?>>En vol</option>
            <option value="Maintenance" <?= $avion["StatutAvion"] === "Maintenance" ? "selected" : "" ?>>Maintenance</option>
            <option value="HorsService" <?= $avion["StatutAvion"] === "HorsService" ? "selected" : "" ?>>Hors service</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Aéroport actuel</label>
        <select name="IdAeroportActuel" class="form-select">
            <?php foreach ($aeroports as $ap): ?>
                <option value="<?= $ap["IdAeroport"] ?>"
                    <?= $avion["IdAeroportActuel"] == $ap["IdAeroport"] ? "selected" : "" ?>>
                    <?= $ap["NomAeroport"] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary w-100">
        Enregistrer
    </button>

</form>


<!-- Suppression -->
<?php if ($avion["StatutAvion"] !== "EnVol"): ?>

    <form action="/admin/avion/delete/<?= $avion["IdAvion"] ?>" 
          method="POST" 
          class="mb-3"
          onsubmit="return confirm('Supprimer cet avion ?')">

        <button type="submit" class="btn btn-danger">
            Supprimer cet avion
        </button>

    </form>

<?php else: ?>

    <div class="alert alert-info">
        Cet avion ne peut pas être supprimé car il est actuellement en vol.
    </div>

<?php endif; ?>


<a href="/admin/avion" class="btn btn-secondary">Retour</a>