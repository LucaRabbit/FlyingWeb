<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>FlyingWeb – Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/admin.css" rel="stylesheet">

    <!-- Icones Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

<?php use App\Helpers\Flash; ?>

<?php foreach (Flash::get() as $type => $messages): ?>
    <?php foreach ($messages as $msg): ?>
        <div class="alert alert-<?= $type ?> alert-dismissible fade show mt-2 mx-2">
            <?= $msg ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>

<div class="d-flex">

    <!-- Sidebar -->
    <div class="sidebar bg-white shadow-sm p-3">
        <h4 class="fw-bold mb-4 text-primary">
            <i class="bi bi-speedometer2 me-1"></i> Admin
        </h4>

        <a href="/admin" class="d-block py-2">
            <i class="bi bi-grid me-2"></i> Tableau de bord
        </a>

        <a href="/admin/vol" class="d-block py-2">
            <i class="bi bi-airplane me-2"></i> Vols
        </a>

        <a href="/admin/reservation" class="d-block py-2">
            <i class="bi bi-ticket-perforated me-2"></i> Réservations
        </a>

        <a href="/admin/avion" class="d-block py-2">
            <i class="bi bi-box-seam me-2"></i> Avions
        </a>

        <a href="/admin/aeroport" class="d-block py-2">
            <i class="bi bi-geo-alt me-2"></i> Aéroports
        </a>

        <a href="/admin/logs" class="d-block py-2">
            <i class="bi bi-journal-text me-2"></i> Logs
        </a>

        <a href="/admin/logout" class="d-block py-2 text-danger mt-4 fw-semibold">
            <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
        </a>
    </div>

    <!-- Contenu -->
    <div class="content flex-grow-1 p-4">
        <?= $content ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>