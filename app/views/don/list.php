<h1>Liste des Dons</h1>

<a href="/">Accueil</a> | <a href="/besoin/list">Besoins</a> | <b>Dons</b> | <a href="/distribution/list">Distributions</a> | <a href="/stock/list">Stock</a>
<br><br>
<a href="/don/form">+ Nouveau don</a>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Type</th>
            <th>Quantité</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($dons)): ?>
            <?php foreach ($dons as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['id']) ?></td>
                    <td><?= htmlspecialchars($d['name']) ?></td>
                    <td><?= htmlspecialchars($d['type_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($d['quantite']) ?></td>
                    <td><?= htmlspecialchars($d['date']) ?></td>
                    <td>
                        <a href="/don/edit?id=<?= $d['id'] ?>">Modifier</a>
                        |
                        <a href="/don/delete?id=<?= $d['id'] ?>" onclick="return confirm('Supprimer ce don ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Aucun don trouvé.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
