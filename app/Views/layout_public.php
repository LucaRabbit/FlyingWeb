<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>FlyingWeb</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/front.css" rel="stylesheet">

    <!-- Icones Bootstrap-->
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

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">

        <!-- Logo / Nom du site -->
        <a class="navbar-brand fw-bold" href="/">
            <i class="bi bi-airplane-fill me-1"></i> FlyingWeb
        </a>

        <!-- Bouton mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="/front">
                        <i class="bi bi-speedometer2 me-1"></i> Tableau de bord
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/vol/recherche">
                        <i class="bi bi-search me-1"></i> Rechercher un vol
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/reservation/acces">
                        <i class="bi bi-ticket-perforated me-1"></i> Ma réservation
                    </a>
                </li>

            </ul>
        </div>

    </div>
</nav>

<div class="container page-wrapper py-5">
    <?= $content ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>