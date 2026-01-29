<h1 class="mb-4 text-center fw-light">Tableau de bord</h1>

<div class="row g-3">

    <!-- Rechercher un vol -->
    <div class="col-md-6">
        <a href="/vol/recherche" class="text-decoration-none">
            <div class="card p-3 text-center soft-card">
                <i class="bi bi-airplane fs-2 text-primary mb-2"></i>
                <h5 class="fw-semibold mb-1">Rechercher un vol</h5>
                <p class="text-muted small mb-0">Trouvez un vol selon vos critères</p>
            </div>
        </a>
    </div>

    <!-- Ma réservation -->
    <div class="col-md-6">
        <a href="/reservation/acces" class="text-decoration-none">
            <div class="card p-3 text-center soft-card">
                <i class="bi bi-ticket-perforated fs-2 text-success mb-2"></i>
                <h5 class="fw-semibold mb-1">Ma réservation</h5>
                <p class="text-muted small mb-0">Consultez ou gérez votre réservation</p>
            </div>
        </a>
    </div>

</div>

<style>
    .soft-card {
        border-radius: 12px;
        transition: 0.2s ease;
    }

    .soft-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.4rem 0.8rem rgba(0,0,0,0.08);
    }
</style>