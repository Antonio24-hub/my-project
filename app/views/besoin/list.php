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
<h1>Liste des Besoins par Ville</h1>

<nav>
<a href="/">Accueil</a> <b>Besoins</b> <a href="/don/list">Dons</a> <a href="/distribution/list">Distributions</a> <a href="/stock/list">Stock</a> <a href="/prix/list">Prix</a> <a href="/achat/list">Achats</a> <a href="/tableau-de-bord">Tableau de bord</a> <a href="/recap">Récap</a>
</nav>

<br>
<a href="/besoin/form"> Nouveau besoin</a>

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

</body>
</html>