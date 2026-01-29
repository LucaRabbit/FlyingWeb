<h1 class="mb-4">Tableau de bord administrateur</h1>

<div class="row g-4">

    <div class="col-md-4">
        <a href="/admin/avion" class="text-decoration-none">
            <div class="card shadow-sm p-4 text-center hover-card">
                <i class="bi bi-box-seam fs-1 text-primary mb-3"></i>
                <h5 class="fw-bold">Gestion des avions</h5>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="/admin/aeroport" class="text-decoration-none">
            <div class="card shadow-sm p-4 text-center hover-card">
                <i class="bi bi-geo-alt fs-1 text-danger mb-3"></i>
                <h5 class="fw-bold">Gestion des aéroports</h5>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="/admin/vol" class="text-decoration-none">
            <div class="card shadow-sm p-4 text-center hover-card">
                <i class="bi bi-airplane fs-1 text-success mb-3"></i>
                <h5 class="fw-bold">Gestion des vols</h5>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="/admin/reservation" class="text-decoration-none">
            <div class="card shadow-sm p-4 text-center hover-card">
                <i class="bi bi-ticket-perforated fs-1 text-warning mb-3"></i>
                <h5 class="fw-bold">Gestion des réservations</h5>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="/admin/logs" class="text-decoration-none">
            <div class="card shadow-sm p-4 text-center hover-card">
                <i class="bi bi-journal-text fs-1 text-secondary mb-3"></i>
                <h5 class="fw-bold">Consulter les logs</h5>
            </div>
        </a>
    </div>

</div>

<style>
    .hover-card {
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .hover-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 .5rem 1.2rem rgba(0,0,0,0.15) !important;
    }
</style>