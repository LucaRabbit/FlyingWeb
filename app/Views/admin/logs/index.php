<h1>Historique des logs</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Table</th>
            <th>Action</th>
            <th>Record ID</th>
            <th>Anciennes données</th>
            <th>Nouvelles données</th>
            <th>Date</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($logs as $log): ?>
            <tr>
                <td><?= $log['IdLog'] ?></td>
                <td><?= htmlspecialchars($log['TableName']) ?></td>
                <td><?= htmlspecialchars($log['Action']) ?></td>
                <td><?= $log['RecordId'] ?></td>

                <td>
                    <pre>
<?= $log['OldData'] ? json_encode(json_decode($log['OldData'], true), JSON_PRETTY_PRINT) : '' ?>
                    </pre>
                </td>

                <td>
                    <pre>
<?= $log['NewData'] ? json_encode(json_decode($log['NewData'], true), JSON_PRETTY_PRINT) : '' ?>
                    </pre>
                </td>

                <td><?= $log['PerformedAt'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
$totalPages = ceil($total / $perPage);
?>

<div>

    <?php if ($page > 1): ?>
        <a href="/admin/logs?page=<?= $page - 1 ?>">← Précédent</a>
    <?php endif; ?>

    <span>
        Page <?= $page ?> / <?= $totalPages ?>
    </span>

    <?php if ($page < $totalPages): ?>
        <a href="/admin/logs?page=<?= $page + 1 ?>">Suivant →</a>
    <?php endif; ?>

</div>