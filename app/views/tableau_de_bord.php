<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC - Tableau de Bord</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/theme.css">
    <link rel="stylesheet" href="/css/effects.css">
</head>
<body>
<h1>Tableau de Bord</h1>

<nav>
<a href="/">Accueil</a> <a href="/besoin/list">Besoins</a> <a href="/don/list">Dons</a> <a href="/distribution/list">Distributions</a> <a href="/stock/list">Stock</a> <a href="/prix/list">Prix</a> <a href="/achat/list">Achats</a> <b>Tableau de bord</b> <a href="/recap">Récap</a>
</nav>

<h2>Récapitulatif par Ville</h2>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Ville</th>
            <th>Région</th>
            <th>Besoins</th>
            <th>Distributions</th>
            <th>Reste</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($villes_stats)): ?>
            <?php foreach ($villes_stats as $v): ?>
                <tr>
                    <td><?= htmlspecialchars($v['ville_name']) ?></td>
                    <td><?= htmlspecialchars($v['region_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($v['total_besoins']) ?></td>
                    <td><?= htmlspecialchars($v['total_distributions']) ?></td>
                    <td><?= htmlspecialchars($v['reste']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Aucune ville trouvée.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<h2>Dons Reçus (Global)</h2>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Type</th>
            <th>Quantité Totale</th>
            <th>Nombre de Dons</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($dons_stats)): ?>
            <?php foreach ($dons_stats as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['type_name']) ?></td>
                    <td><?= number_format((float)$d['total_quantite'], 2, ',', ' ') ?> <?= htmlspecialchars($d['unite'] ?? '') ?></td>
                    <td><?= htmlspecialchars($d['nb_dons']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">Aucun don trouvé.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<h2>Achats Effectués (avec l'argent)</h2>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Type</th>
            <th>Montant Total (Ariary)</th>
            <th>Nombre d'Achats</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($achats_stats)): ?>
            <?php foreach ($achats_stats as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['type_name']) ?></td>
                    <td><?= number_format((float)$a['total_montant'], 0, ',', ' ') ?> Ar</td>
                    <td><?= htmlspecialchars($a['nb_achats']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">Aucun achat trouvé.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<h2>Totaux Généraux</h2>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Indicateur</th>
            <th>Valeur</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Total Besoins enregistrés</td>
            <td><?= htmlspecialchars($totaux['total_besoins'] ?? 0) ?></td>
        </tr>
        <tr>
            <td>Total Distributions effectuées</td>
            <td><?= htmlspecialchars($totaux['total_distributions'] ?? 0) ?></td>
        </tr>
        <tr>
            <td>Total Dons reçus</td>
            <td><?= htmlspecialchars($totaux['total_dons'] ?? 0) ?></td>
        </tr>
        <tr>
            <td>Total Achats effectués</td>
            <td><?= htmlspecialchars($totaux['total_achats'] ?? 0) ?></td>
        </tr>
        <tr>
            <td>Montant total des achats</td>
            <td><?= number_format((float)($totaux['montant_achats'] ?? 0), 0, ',', ' ') ?> Ariary</td>
        </tr>
    </tbody>
</table>

</body>
</html>
