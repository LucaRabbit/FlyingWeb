<h1 class="mb-4">Accéder à ma réservation</h1>

<form action="/reservation/show" method="GET" autocomplete="off"  class="card p-4 shadow-sm">

    <div class="mb-3">
        <label class="form-label">Votre code de réservation :</label>
        <input type="text" name="token" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">
        Voir ma réservation
    </button>

</form>