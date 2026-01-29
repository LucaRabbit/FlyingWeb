<h1 class="mb-4 text-center">Connexion administrateur</h1>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger text-center">
        <?= $error ?>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-center">
    <form method="POST" action="/admin/login" class="card p-4 shadow-sm" style="max-width: 400px; width: 100%;">

        <div class="text-center mb-3">
            <i class="bi bi-shield-lock-fill fs-1 text-primary"></i>
        </div>

        <div class="mb-3">
            <label class="form-label">Email :</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mot de passe :</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Se connecter
        </button>

    </form>
</div>