<h1 class="mb-4">Créer une réservation</h1>

<form method="POST" action="/admin/reservation/store" class="card p-4 shadow-sm" style="max-width: 650px;">

    <div class="mb-3">
        <label class="form-label">Date de réservation</label>
        <input type="datetime-local" name="DateReservation" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Nombre de passagers</label>
        <input type="number" name="NbPassagersReserve" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email réservant</label>
        <input type="email" name="EmailReservant" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Statut de la réservation</label>
        <select name="StatutReservation" class="form-select" required>
            <option value="EnCours">En cours</option>
            <option value="Confirmee">Confirmée</option>
            <option value="Annulee">Annulée</option>
            <option value="Cloturee">Clôturée</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Vol</label>
        <select name="IdVol" class="form-select" required>
            <?php foreach ($vols as $v): ?>
                <option value="<?= $v["IdVol"] ?>">
                    Vol #<?= $v["IdVol"] ?> (<?= $v["NumeroVol"] ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-success w-100">Créer</button>

</form>

<a href="/admin/reservation" class="btn btn-secondary mt-3">Retour</a>