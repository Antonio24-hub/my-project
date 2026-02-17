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
<h1>Liste des Achats</h1>

<nav>
<a href="/">Accueil</a> <a href="/besoin/list">Besoins</a> <a href="/don/list">Dons</a> <a href="/distribution/list">Distributions</a> <a href="/stock/list">Stock</a> <a href="/prix/list">Prix</a> <b>Achats</b> <a href="/tableau-de-bord">Tableau de bord</a> <a href="/recap">Récap</a>
</nav>

<br>
<a href="/achat/form">+ Nouvel achat</a>

<form method="GET" action="/achat/list" style="margin: 15px 0;">
    <label for="id_ville">Filtrer par ville :</label>
    <select name="id_ville" id="id_ville">
        <option value="">Toutes</option>
        <?php if (!empty($villes)): ?>
            <?php foreach ($villes as $v): ?>
                <option value="<?= htmlspecialchars($v['id']) ?>" <?= ($selected_ville == $v['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($v['name']) ?>
                </option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
    <button type="submit">Filtrer</button>
</form>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Produit</th>
            <th>Type</th>
            <th>Ville</th>
            <th>Quantité</th>
            <th>Unité</th>
            <th>Prix unitaire</th>
            <th>Montant (Ariary)</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($achats)): ?>
            <?php foreach ($achats as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['id']) ?></td>
                    <td><?= htmlspecialchars($a['produit_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($a['type_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($a['ville_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($a['quantite']) ?></td>
                    <td><?= htmlspecialchars($a['unite'] ?? '') ?></td>
                    <td><?= number_format((float)($a['prix_unitaire'] ?? 0), 2, ',', ' ') ?></td>
                    <td><?= number_format((float)$a['montant'], 2, ',', ' ') ?></td>
                    <td><?= htmlspecialchars($a['date']) ?></td>
                    <td>
                        <a href="/achat/delete?id=<?= $a['id'] ?>" onclick="return confirm('Supprimer cet achat ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="10">Aucun achat trouvé.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>