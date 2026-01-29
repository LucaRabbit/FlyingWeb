<h1 class="mb-4">Votre réservation</h1>

<p><strong>Numéro de réservation :</strong> <?= $reservation["IdReservation"] ?></p>

<p><strong>Date de réservation :</strong>
    <?= date("d/m/Y H:i", strtotime($reservation["DateReservation"])) ?>
</p>

<p><strong>Email du réservant :</strong> <?= htmlspecialchars($reservation["EmailReservant"]) ?></p>

<p><strong>Statut :
    <?php if ($reservation["StatutReservation"] === "Confirmee"): ?>
        <span class="badge bg-success">Confirmée</span>
    <?php elseif ($reservation["StatutReservation"] === "EnAttente"): ?>
        <span class="badge bg-warning text-dark">En attente</span>
    <?php elseif ($reservation["StatutReservation"] === "Annulee"): ?>
        <span class="badge bg-danger">Annulée</span>
    <?php elseif ($reservation["StatutReservation"] === "AnnuleeVol"): ?>
        <span class="badge bg-danger">Vol Annulé</span>
    <?php else: ?>
        <span class="badge bg-secondary"><?= $r["StatutReservation"] ?></span>
    <?php endif; ?>
</p>


<!-- VOL ALLER -->
<h2 class="h4 mt-4 mb-3">Vol aller</h2>

<table class="table table-bordered table-striped shadow-sm mb-4">
    <thead class="table-primary">
        <tr>
            <th>Numéro</th>
            <th>Départ</th>
            <th>Aéroport départ</th>
            <th>Arrivée</th>
            <th>Aéroport arrivée</th>
            <th>Durée</th>
            <th>Statut</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td><?= $volAller["NumeroVol"] ?></td>

            <td><?= date("d/m/Y H:i", strtotime($volAller["DateHeureDepartUTC"])) ?></td>

            <td>
                <?= htmlspecialchars($volAller["AeroportDepartNom"]) ?>
                (<?= htmlspecialchars($volAller["VilleDepart"]) ?>)
            </td>

            <td><?= date("d/m/Y H:i", strtotime($volAller["DateHeureArriveeUTC"])) ?></td>

            <td>
                <?= htmlspecialchars($volAller["AeroportArriveeNom"]) ?>
                (<?= htmlspecialchars($volAller["VilleArrivee"]) ?>)
            </td>

            <!-- Durée -->
            <td>
                <?php
                    $d = strtotime($volAller["DateHeureDepartUTC"]);
                    $a = strtotime($volAller["DateHeureArriveeUTC"]);
                    echo gmdate("H\hi", $a - $d);
                ?>
            </td>

            <td>
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
            </td>
        </tr>
    </tbody>
</table>


<!-- PASSAGERS ALLER -->
<h2 class="h5 mt-4 mb-3">Passagers (Aller)</h2>

<ul class="list-group mb-4">
<?php foreach ($passagers as $p): ?>
    <?php if ($p["IdVol"] == $volAller["IdVol"]): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>
                <?= htmlspecialchars($p["Nom"]) ?> <?= htmlspecialchars($p["Prenom"]) ?>
                — Siège <?= $p["NumeroSiege"] ?>
            </span>

            <?php if ($modifiable): ?>
                <a href="/reservation/remove-passager?token=<?= $reservation['TokenLien'] ?>&idPassager=<?= $p['IdPassager'] ?>"
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


<!-- VOL RETOUR -->
<?php if ($volRetour): ?>

<h2 class="h4 mt-4 mb-3">Vol retour</h2>

<table class="table table-bordered table-striped shadow-sm mb-4">
    <thead class="table-secondary">
        <tr>
            <th>Numéro</th>
            <th>Départ</th>
            <th>Aéroport départ</th>
            <th>Arrivée</th>
            <th>Aéroport arrivée</th>
            <th>Durée</th>
            <th>Statut<th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td><?= $volRetour["NumeroVol"] ?></td>

            <td><?= date("d/m/Y H:i", strtotime($volRetour["DateHeureDepartUTC"])) ?></td>

            <td>
                <?= htmlspecialchars($volRetour["AeroportDepartNom"]) ?>
                (<?= htmlspecialchars($volRetour["VilleDepart"]) ?>)
            </td>

            <td><?= date("d/m/Y H:i", strtotime($volRetour["DateHeureArriveeUTC"])) ?></td>

            <td>
                <?= htmlspecialchars($volRetour["AeroportArriveeNom"]) ?>
                (<?= htmlspecialchars($volRetour["VilleArrivee"]) ?>)
            </td>

            <!-- Durée -->
            <td>
                <?php
                    $d = strtotime($volRetour["DateHeureDepartUTC"]);
                    $a = strtotime($volRetour["DateHeureArriveeUTC"]);
                    echo gmdate("H\hi", $a - $d);
                ?>
            </td>

            <td>
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
            </td>
        </tr>
    </tbody>
</table>


<!-- PASSAGERS RETOUR -->
<h2 class="h5 mt-4 mb-3">Passagers (Retour)</h2>

<ul class="list-group mb-4">
<?php foreach ($passagers as $p): ?>
    <?php if ($p["IdVol"] == $volRetour["IdVol"]): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>
                <?= htmlspecialchars($p["Nom"]) ?> <?= htmlspecialchars($p["Prenom"]) ?>
                — Siège <?= $p["NumeroSiege"] ?>
            </span>

            <?php if ($modifiable): ?>
                <a href="/reservation/remove-passager?token=<?= $reservation['TokenLien'] ?>&idPassager=<?= $p['IdPassager'] ?>"
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

<?php endif; ?>


<!-- ACTIONS -->
<div class="mt-4 d-flex flex-column gap-2">

    <a href="/front" class="btn btn-secondary">Retour à l’accueil</a>

    <?php if ($modifiable): ?>

        <a href="/reservation/add-passager?token=<?= $reservation['TokenLien'] ?>"
           class="btn btn-outline-primary">
            Ajouter un passager
        </a>

        <a href="/reservation/cancel?token=<?= $reservation['TokenLien'] ?>"
           class="btn btn-outline-danger"
           onclick="return confirm('Annuler cette réservation ?');">
            Annuler la réservation
        </a>

    <?php else: ?>
        <button class="btn btn-sm btn-outline-secondary" disabled>
            Ajouter un passager
        </button>
    <?php endif; ?>

</div>