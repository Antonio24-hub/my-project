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
<h1>Liste des Distributions</h1>

<nav>
<a href="/">Accueil</a> <a href="/besoin/list">Besoins</a> <a href="/don/list">Dons</a> <b>Distributions</b> <a href="/stock/list">Stock</a> <a href="/prix/list">Prix</a> <a href="/achat/list">Achats</a> <a href="/tableau-de-bord">Tableau de bord</a> <a href="/recap">Récap</a>
</nav>

<br>
<a href="/distribution/form"> Nouvelle distribution</a>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Produit</th>
            <th>Type</th>
            <th>Ville</th>
            <th>Besoin lié</th>
            <th>Date</th>
            <th>Quantité</th>
            <th>Unité</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($distributions)): ?>
            <?php foreach ($distributions as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['id']) ?></td>
                    <td><?= htmlspecialchars($d['name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($d['type_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($d['ville_name'] ?? '') ?></td>
                    <td><?= $d['id_besoin'] ? htmlspecialchars($d['besoin_name'] ?? '') : '<em>Libre</em>' ?></td>
                    <td><?= htmlspecialchars($d['date']) ?></td>
                    <td><?= htmlspecialchars($d['quantite']) ?></td>
                    <td><?= htmlspecialchars($d['unite'] ?? '') ?></td>
                    <td>
                        <a href="/distribution/edit?id=<?= $d['id'] ?>">Modifier</a>
                        |
                        <a href="/distribution/delete?id=<?= $d['id'] ?>" onclick="return confirm('Supprimer cette distribution ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9">Aucune distribution trouvée.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>