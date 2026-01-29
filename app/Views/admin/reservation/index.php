<h1 class="mb-4">Liste des réservations</h1>

<?php if (empty($reservations)): ?>

    <div class="alert alert-warning">
        Aucune réservation trouvée.
    </div>

<?php else: ?>

<table class="table table-striped table-hover shadow-sm">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Email</th>
            <th>Vol aller</th>
            <th>Vol retour</th>
            <th>Passagers</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($reservations as $r): ?>
        <tr>
            <td><?= $r["IdReservation"] ?></td>
            <td><?= date("d/m/Y H:i", strtotime($r["DateReservation"])) ?></td>
            <td><?= $r["EmailReservant"] ?></td>
            <td><?= $r["VolAller"]["NumeroVol"] ?></td>

            <td>
                <?php if (!$r["VolRetour"]): ?>
                    <span class="text-muted">-</span>
                <?php else: ?>
                    <?= $r["VolRetour"]["NumeroVol"] ?>
                <?php endif; ?>
            </td>

            <td><?= $r["NbPassagersReserve"] ?></td>

            <td>
                <?php if ($r["StatutReservation"] === "Confirmee"): ?>
                    <span class="badge bg-success">Confirmée</span>
                <?php elseif ($r["StatutReservation"] === "EnAttente"): ?>
                    <span class="badge bg-warning text-dark">En attente</span>
                <?php elseif ($r["StatutReservation"] === "Annulee"): ?>
                    <span class="badge bg-danger">Annulée</span>
                <?php elseif ($r["StatutReservation"] === "AnnuleeVol"): ?>
                    <span class="badge bg-danger">Vol Annulé</span>
                <?php else: ?>
                    <span class="badge bg-secondary"><?= $r["StatutReservation"] ?></span>
                <?php endif; ?>
            </td>

            <td>
                <a href="/admin/reservation/show/<?= $r["IdReservation"] ?>" class="btn btn-sm btn-outline-secondary">
                    Voir
                </a>

                <?php if ($r["modifiable"]): ?>
                    <a href="/admin/reservation/edit/<?= $r["IdReservation"] ?>" class="btn btn-sm btn-outline-primary ms-1">
                        Modifier
                    </a>
                <?php else: ?>
                        <button class="btn btn-sm btn-outline-secondary ms-1" disabled>
                            Modifier
                        </button>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>