<h1>Liste des Distributions</h1>

<a href="/">Accueil</a> | <a href="/besoin/list">Besoins</a> | <a href="/don/list">Dons</a> | <b>Distributions</b> | <a href="/stock/list">Stock</a>
<br><br>
<a href="/distribution/form">+ Nouvelle distribution</a>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Besoin</th>
            <th>Date</th>
            <th>Quantité</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($distributions)): ?>
            <?php foreach ($distributions as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['id']) ?></td>
                    <td><?= htmlspecialchars($d['besoin_name'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($d['date']) ?></td>
                    <td><?= htmlspecialchars($d['quantite']) ?></td>
                    <td>
                        <a href="/distribution/edit?id=<?= $d['id'] ?>">Modifier</a>
                        |
                        <a href="/distribution/delete?id=<?= $d['id'] ?>" onclick="return confirm('Supprimer cette distribution ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Aucune distribution trouvée.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
