<h1 class="mb-4">Modifier l'aéroport #<?= $aeroport["IdAeroport"] ?></h1>

<form method="POST" 
      action="/admin/aeroport/update/<?= $aeroport["IdAeroport"] ?>" 
      class="card p-4 shadow-sm" 
      style="max-width: 650px;">

    <div class="mb-3">
        <label class="form-label">Code IATA</label>
        <input type="text" 
               name="CodeIATA" 
               maxlength="3" 
               value="<?= $aeroport["CodeIATA"] ?>" 
               class="form-control" 
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">Nom officiel</label>
        <input type="text" 
               name="NomOfficiel" 
               value="<?= $aeroport["NomOfficiel"] ?>" 
               class="form-control" 
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">Ville</label>
        <input type="text" 
               name="Ville" 
               value="<?= $aeroport["Ville"] ?>" 
               class="form-control" 
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">Pays</label>
        <input type="text" 
               name="Pays" 
               value="<?= $aeroport["Pays"] ?>" 
               class="form-control" 
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">Longueur piste max (m)</label>
        <input type="number" 
               name="LongueurAvionMax" 
               value="<?= $aeroport["LongueurAvionMax"] ?>" 
               class="form-control">
    </div>

    <button type="submit" class="btn btn-primary w-100">
        Mettre à jour
    </button>

</form>

<a href="/admin/aeroport" class="btn btn-secondary mt-3">Retour</a>