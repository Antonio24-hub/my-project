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
<h1>Liste des Prix Unitaires</h1>

<nav>
<a href="/">Accueil</a> <a href="/besoin/list">Besoins</a> <a href="/don/list">Dons</a> <a href="/distribution/list">Distributions</a> <a href="/stock/list">Stock</a> <b>Prix</b> <a href="/achat/list">Achats</a> <a href="/tableau-de-bord">Tableau de bord</a> <a href="/recap">Récap</a>
</nav>

<br>
<a href="/prix/form"> Nouveau prix</a>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Type</th>
            <th>Prix unitaire (Ariary)</th>
            <th>Unité</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($prix_list)): ?>
            <?php foreach ($prix_list as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['id']) ?></td>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= htmlspecialchars($p['type_name'] ?? '') ?></td>
                    <td><?= number_format((float)$p['prix_unitaire'], 2, ',', ' ') ?></td>
                    <td><?= htmlspecialchars($p['unite'] ?? '') ?></td>
                    <td>
                        <a href="/prix/edit?id=<?= $p['id'] ?>">Modifier</a>
                        |
                        <a href="/prix/delete?id=<?= $p['id'] ?>" onclick="return confirm('Supprimer ce prix ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Aucun prix défini.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>