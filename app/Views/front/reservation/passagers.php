<h1 class="mb-4">Ajouter les passagers</h1>

<form action="/reservation/save-passagers" method="POST" autocomplete="off" class="card p-4 shadow-sm">

    <?php for ($i = 1; $i <= $reservation["NbPassagersReserve"]; $i++): ?>
        
        <fieldset class="border rounded p-3 mb-4">
            <legend class="float-none w-auto px-2"><?= "Passager $i" ?></legend>

            <div class="mb-3">
                <label class="form-label">Nom :</label>
                <input type="text" name="Nom[]" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Prénom :</label>
                <input type="text" name="Prenom[]" class="form-control" required>
            </div>

        </fieldset>

    <?php endfor; ?>

    <button type="submit" class="btn btn-primary w-100">Continuer</button>
</form>