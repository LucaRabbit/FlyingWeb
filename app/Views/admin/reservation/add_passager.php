<h1 class="mb-4">Ajouter un passager à la réservation #<?= $reservation["IdReservation"] ?></h1>

<form action="/admin/reservation/add-passager/<?= $reservation["IdReservation"] ?>" 
      method="POST" 
      autocomplete="off" 
      class="card p-4 shadow-sm" 
      style="max-width: 500px;">

    <div class="mb-3">
        <label class="form-label">Nom</label>
        <input type="text" name="Nom" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Prénom</label>
        <input type="text" name="Prenom" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">
        Ajouter
    </button>

</form>