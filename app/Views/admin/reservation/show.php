<h1 class="mb-4">Réservation #<?= $reservation["IdReservation"] ?></h1>

<!-- Informations générales -->
<div class="card p-4 shadow-sm mb-4">
    <h2 class="h5 mb-3">Informations générales</h2>

    <p><strong>Date de réservation :</strong>
        <?= date("d/m/Y H:i", strtotime($reservation["DateReservation"])) ?>
    </p>

    <p><strong>Email réservant :</strong> <?= $reservation["EmailReservant"] ?></p>

    <p><strong>Statut :</strong>
        <?php if ($reservation["StatutReservation"] === "Confirmee"): ?>
            <span class="badge bg-success">Confirmée</span>
        <?php elseif ($reservation["StatutReservation"] === "EnAttente"): ?>
            <span class="badge bg-warning text-dark">En attente</span>
        <?php elseif ($reservation["StatutReservation"] === "Annulee"): ?>
            <span class="badge bg-danger">Annulée</span>
        <?php elseif ($reservation["StatutReservation"] === "AnnuleeVol"): ?>
            <span class="badge bg-danger">Annulée (Vol annulé)</span>
        <?php else: ?>
            <span class="badge bg-secondary"><?= $reservation["StatutReservation"] ?></span>
        <?php endif; ?>
    </p>

    <p><strong>Nombre de passagers :</strong> <?= $reservation["NbPassagersReserve"] ?></p>
</div>


<!-- Vol aller -->
<div class="card p-4 shadow-sm mb-4">
    <h2 class="h5 mb-3">Vol aller</h2>

    <?php if ($volAller): ?>
        <p><strong>Numéro :</strong> <?= $volAller["NumeroVol"] ?></p>
        <p><strong>Départ :</strong> <?= $volAller["DateHeureDepartUTC"] ?></p>
        <p><strong>Arrivée :</strong> <?= $volAller["DateHeureArriveeUTC"] ?></p>
        <p><strong>Statut :</strong>
                <?php if ($volAller["StatutVol"] === "Planifie"): ?>
                    <span class="badge bg-primary">Planifié</span>
                <?php elseif ($volAller["StatutVol"] === "EnCours"): ?>
                    <span class="badge bg-info text-dark">En cours</span>
                <?php elseif ($volAller["StatutVol"] === "Arrive"): ?>
                    <span class="badge bg-success">Arrivé</span>
                <?php elseif ($volAller["StatutVol"] === "Annule"): ?>
                    <span class="badge bg-danger">Annulé</span>
                <?php elseif ($volAller["StatutVol"] === "AnnuleVol"): ?>
                    <span class="badge bg-danger">Annulé</span>
                <?php else: ?>
                    <span class="badge bg-secondary"><?= $volAller["StatutVol"] ?></span>
                <?php endif; ?>
        </p>
    <?php else: ?>
        <p class="text-muted"><em>Aucun vol aller trouvé.</em></p>
    <?php endif; ?>
</div>


<!-- Passagers aller -->
<div class="card p-4 shadow-sm mb-4">
    <h2 class="h5 mb-3">Passagers (Aller)</h2>

    <ul class="list-group">
    <?php foreach ($passagers as $p): ?>
        <?php if ($p["IdVol"] == $volAller["IdVol"]): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><?= $p["Nom"] ?> <?= $p["Prenom"] ?> — Siège <?= $p["NumeroSiege"] ?></span>

                <?php if ($modifiable): ?>
                    <a href="/admin/reservation/remove-passager/<?= $reservation["IdReservation"] ?>/<?= $p["IdPassager"] ?>"
                       class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Supprimer ce passager ?');">
                        Supprimer
                    </a>
                <?php else: ?>
                    <button class="btn btn-sm btn-outline-secondary" disabled>
                            Supprimer
                    </button>
                <?php endif; ?>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
    </ul>
</div>


<!-- Vol retour -->
<div class="card p-4 shadow-sm mb-4">
    <h2 class="h5 mb-3">Vol retour</h2>

    <?php if ($volRetour): ?>
        <p><strong>Numéro :</strong> <?= $volRetour["NumeroVol"] ?></p>
        <p><strong>Départ :</strong> <?= $volRetour["DateHeureDepartUTC"] ?></p>
        <p><strong>Arrivée :</strong> <?= $volRetour["DateHeureArriveeUTC"] ?></p>
        <p><strong>Statut :</strong>
                <?php if ($volRetour["StatutVol"] === "Planifie"): ?>
                    <span class="badge bg-primary">Planifié</span>
                <?php elseif ($volRetour["StatutVol"] === "EnCours"): ?>
                    <span class="badge bg-info text-dark">En cours</span>
                <?php elseif ($volRetour["StatutVol"] === "Arrive"): ?>
                    <span class="badge bg-success">Arrivé</span>
                <?php elseif ($volRetour["StatutVol"] === "Annule"): ?>
                    <span class="badge bg-danger">Annulé</span>
                <?php elseif ($volRetour["StatutVol"] === "AnnuleVol"): ?>
                    <span class="badge bg-danger">Annulé</span>
                <?php else: ?>
                    <span class="badge bg-secondary"><?= $volRetour["StatutVol"] ?></span>
                <?php endif; ?>
        </p>
    <?php else: ?>
        <p class="text-muted"><em>Aucun vol retour.</em></p>
    <?php endif; ?>
</div>


<!-- Passagers retour -->
<?php if ($volRetour): ?>
<div class="card p-4 shadow-sm mb-4">
    <h2 class="h5 mb-3">Passagers (Retour)</h2>

    <ul class="list-group">
    <?php foreach ($passagers as $p): ?>
        <?php if ($p["IdVol"] == $volRetour["IdVol"]): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><?= $p["Nom"] ?> <?= $p["Prenom"] ?> — Siège <?= $p["NumeroSiege"] ?></span>

                <?php if ($modifiable): ?>
                    <a href="/admin/reservation/remove-passager/<?= $reservation["IdReservation"] ?>/<?= $p["IdPassager"] ?>"
                       class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Supprimer ce passager ?');">
                        Supprimer
                    </a>
                <?php else: ?>
                    <button class="btn btn-sm btn-outline-secondary" disabled>
                        Supprimer
                    </button>
                <?php endif; ?>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>


<!-- Actions -->
<div class="d-flex gap-2 mt-3">
    <a href="/admin/reservation" class="btn btn-secondary">Retour</a>

    <?php if ($modifiable): ?>
        <a href="/admin/reservation/add-passager/<?= $reservation['IdReservation'] ?>" class="btn btn-primary">
            Ajouter un passager
        </a>
    <?php else: ?>
        <button class="btn btn-sm btn-outline-secondary" disabled>
            Ajouter un passager
        </button>
    <?php endif; ?>
</div>