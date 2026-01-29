<h1 class="mb-4">Modifier la réservation #<?= $reservation["IdReservation"] ?></h1>

<form action="/admin/reservation/update/<?= $reservation["IdReservation"] ?>" 
      method="POST" 
      class="card p-4 shadow-sm" 
      style="max-width: 650px;">

    <div class="mb-3">
        <label class="form-label">Date de réservation</label>
        <input type="text" 
               name="DateReservation" 
               value="<?= $reservation["DateReservation"] ?>" 
               class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Email réservant</label>
        <input type="email" 
               name="EmailReservant" 
               value="<?= $reservation["EmailReservant"] ?>" 
               class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Nombre de passagers</label>
        <input type="number" 
               name="NbPassagersReserve" 
               value="<?= $reservation["NbPassagersReserve"] ?>" 
               class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Token lien</label>
        <input type="text" 
               name="TokenLien" 
               value="<?= $reservation["TokenLien"] ?>" 
               class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Statut</label>
        <select name="StatutReservation" class="form-select">
            <option value="EnAttente"   <?= $reservation["StatutReservation"] == "EnAttente" ? "selected" : "" ?>>En attente</option>
            <option value="Annulee"     <?= $reservation["StatutReservation"] == "Annulee" ? "selected" : "" ?>>Annulée</option>
            <option value="Confirmee"   <?= $reservation["StatutReservation"] == "Confirmee" ? "selected" : "" ?>>Confirmée</option>
            <option value="Cloturee"    <?= $reservation["StatutReservation"] == "Cloturee" ? "selected" : "" ?>>Clôturée</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary w-100">
        Enregistrer
    </button>

</form>