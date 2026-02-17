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
<h1>Liste des Dons</h1>

<nav>
<a href="/">Accueil</a> <a href="/besoin/list">Besoins</a> <b>Dons</b> <a href="/distribution/list">Distributions</a> <a href="/stock/list">Stock</a> <a href="/prix/list">Prix</a> <a href="/achat/list">Achats</a> <a href="/tableau-de-bord">Tableau de bord</a> <a href="/recap">Récap</a>
</nav>

<br>
<a href="/don/form"> Nouveau don</a>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Type</th>
            <th>Quantité</th>
            <th>Unité</th>
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
                    <td><?= htmlspecialchars($d['unite'] ?? '') ?></td>
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
                <td colspan="7">Aucun don trouvé.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>