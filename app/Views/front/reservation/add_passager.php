<h1 class="mb-4">
    Ajouter un passager à la réservation #<?= $reservation["IdReservation"] ?>
</h1>

<form action="/reservation/add-passager" method="POST" autocomplete="off" class="card p-4 shadow-sm">

    <input type="hidden" name="token" value="<?= $reservation["TokenLien"] ?>">

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