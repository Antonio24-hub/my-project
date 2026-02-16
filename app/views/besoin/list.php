<h1>Liste des Besoins par Ville</h1>

<a href="/">Accueil</a> | <b>Besoins</b> | <a href="/don/list">Dons</a> | <a href="/distribution/list">Distributions</a> | <a href="/stock/list">Stock</a>
<br><br>
<a href="/besoin/form">+ Nouveau besoin</a>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Type</th>
            <th>Ville</th>
            <th>Région</th>
            <th>Quantité</th>
            <th>Unité</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($besoins)): ?>
            <?php foreach ($besoins as $b): ?>
                <tr>
                    <td><?= htmlspecialchars($b['id']) ?></td>
                    <td><?= htmlspecialchars($b['name']) ?></td>
                    <td><?= htmlspecialchars($b['type_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($b['ville_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($b['region_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($b['quantite']) ?></td>
                    <td><?= htmlspecialchars($b['unite'] ?? '') ?></td>
                    <td>
                        <a href="/besoin/edit?id=<?= $b['id'] ?>">Modifier</a>
                        |
                    
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">Aucun besoin trouvé.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
