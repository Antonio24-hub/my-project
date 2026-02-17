<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC - Gestion des catastrophes</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/theme.css">
    <link rel="stylesheet" href="/css/effects.css">
</head>
<body>
<h1>Stock disponible</h1>

<nav>
<a href="/">Accueil</a> <a href="/besoin/list">Besoins</a> <a href="/don/list">Dons</a> <a href="/distribution/list">Distributions</a> <b>Stock</b> <a href="/prix/list">Prix</a> <a href="/achat/list">Achats</a> <a href="/tableau-de-bord">Tableau de bord</a> <a href="/recap">Récap</a>
</nav>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Type</th>
            <th>Quantité en stock</th>
            <th>Unité</th>
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
                    <td><?= htmlspecialchars($s['unite'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Aucun stock.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>