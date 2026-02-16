<h1>Stock disponible</h1>

<a href="/">Accueil</a> | <a href="/besoin/list">Besoins</a> | <a href="/don/list">Dons</a> | <a href="/distribution/list">Distributions</a> | <b>Stock</b>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Type</th>
            <th>Quantité en stock</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($stocks)): ?>
            <?php foreach ($stocks as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['id']) ?></td>
                    <td><?= htmlspecialchars($s['name']) ?></td>
                    <td><?= htmlspecialchars($s['type_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($s['quantite']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Aucun stock.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
