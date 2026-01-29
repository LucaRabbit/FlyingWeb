<h1 class="mb-4">Ajouter un aéroport</h1>

<form method="POST" action="/admin/aeroport/store" 
      class="card p-4 shadow-sm" 
      style="max-width: 650px;">

    <div class="mb-3">
        <label class="form-label">Code IATA</label>
        <input type="text" name="CodeIATA" maxlength="3" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Nom officiel</label>
        <input type="text" name="NomOfficiel" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Ville</label>
        <input type="text" name="Ville" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Pays</label>
        <input type="text" name="Pays" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Longueur piste max (m)</label>
        <input type="number" name="LongueurPisteMax" class="form-control">
    </div>

    <button type="submit" class="btn btn-success w-100">
        Créer
    </button>

</form>

<a href="/admin/aeroport" class="btn btn-secondary mt-3">Retour</a>