<div class="text-center mb-5">
    <h1 class="fw-bold mb-3">Bienvenue sur FlyingWeb</h1>
    <p class="text-muted fs-5">Votre portail pour rechercher des vols et gérer vos réservations</p>
</div>

<div class="row justify-content-center g-4">

    <!-- Carte : Rechercher un vol -->
    <div class="col-md-5">
        <div class="card border-0 shadow-sm h-100 hover-card">
            <div class="card-body text-center py-4">
                <div class="mb-3">
                    <span class="badge bg-primary fs-6">
                        <i class="bi bi-airplane me-1"></i> Vols
                    </span>
                </div>
                <h2 class="h5 mb-3">Rechercher un vol</h2>
                <p class="text-muted mb-4">Consultez les vols disponibles et effectuez une réservation.</p>
                <a href="/vol/recherche" class="btn btn-primary px-4">Accéder</a>
            </div>
        </div>
    </div>

    <!-- Carte : Administration -->
    <div class="col-md-5">
        <div class="card border-0 shadow-sm h-100 hover-card">
            <div class="card-body text-center py-4">
                <div class="mb-3">
                    <span class="badge bg-secondary fs-6">
                        <i class="bi bi-gear-fill me-1"></i> Administration
                    </span>
                </div>
                <h2 class="h5 mb-3">Administration</h2>
                <p class="text-muted mb-4">Gérez les vols, réservations et paramètres du système.</p>
                <a href="/admin/login" class="btn btn-outline-secondary px-4">Connexion</a>
            </div>
        </div>
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